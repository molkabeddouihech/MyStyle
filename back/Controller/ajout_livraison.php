<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/livraison.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    // Vérification méthode HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }

    // Récupération des données
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    
    if (!$data) {
        throw new Exception('Données invalides');
    }

    // Validation des champs
    $requiredFields = [
        'date_livraison' => 'date',
        'adresse_livraison' => 'string',
        'id_commande' => 'integer',
        'ville' => 'string',
        'code_postal' => 'string'
    ];
    
    foreach ($requiredFields as $field => $type) {
        if (!isset($data[$field])) {
            throw new Exception("Le champ $field est obligatoire");
        }
        
        // Validation basique du type
        if ($type === 'integer' && !is_numeric($data[$field])) {
            throw new Exception("Le champ $field doit être un nombre");
        }
    }

    // Construction de l'adresse complète
    $adresse_complete = $data['adresse_livraison'] . ', ' . $data['ville'] . ' ' . $data['code_postal'];

    // Vérification que la commande existe
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("SELECT id_commande FROM commande WHERE id_commande = ?");
    $stmt->execute([$data['id_commande']]);
    if (!$stmt->fetch()) {
        throw new Exception("La commande spécifiée n'existe pas");
    }

    // Création et insertion de la livraison
    $livraison = new Livraison(
        null,
        $data['date_livraison'],
        $data['statut_livraison'] ?? 'en attente',
        $adresse_complete,
        $data['id_commande']
    );

    $stmt = $pdo->prepare("INSERT INTO livraison 
                         (date_livraison, statut_livraison, adresse_livraison, id_commande) 
                         VALUES (?, ?, ?, ?)");
    
    $success = $stmt->execute([
        $livraison->getDateLivraison(),
        $livraison->getStatutLivraison(),
        $livraison->getAdresseLivraison(),
        $livraison->getIdCommande()
    ]);

    if ($success) {
        $response['success'] = true;
        $response['message'] = 'Livraison enregistrée avec succès';
        $response['livraison_id'] = $pdo->lastInsertId();
        
        // Stocker en session pour affichage
        $_SESSION['livraison_data'] = [
            'id_commande' => $data['id_commande'],
            'id_livraison' => $pdo->lastInsertId(),
            'adresse' => $adresse_complete,
            'date' => $data['date_livraison'],
            'statut' => $livraison->getStatutLivraison()
        ];
        
        // Mettre à jour le statut de la commande
        $stmt = $pdo->prepare("UPDATE commande SET statut_commande = 'en livraison' WHERE id_commande = ?");
        $stmt->execute([$data['id_commande']]);
    } else {
        throw new Exception("Erreur lors de l'insertion");
    }

} catch (PDOException $e) {
    $response['message'] = "Erreur base de données: " . $e->getMessage();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
