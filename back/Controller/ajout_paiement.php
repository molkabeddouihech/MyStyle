<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/paiement.php';

// Configuration des erreurs
ini_set('display_errors', 0); // Désactivé en production
ini_set('log_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', __DIR__.'/paiement_errors.log');

// Nettoyage des buffers et header JSON
while (ob_get_level()) ob_end_clean();
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

// Fonction pour envoyer une réponse JSON standardisée
function sendJsonResponse($success, $data = [], $errorCode = 500) {
    $response = ['success' => $success];
    if ($success) {
        $response = array_merge($response, $data);
    } else {
        $response['error'] = $data;
        http_response_code($errorCode);
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Vérification méthode HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée', 405);
    }

    // Récupération des données (supporte FormData et JSON)
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    // Validation des données
    $paymentMethod = $input['paymentMethod'] ?? null;
    $id_livraison = isset($input['id_livraison']) ? intval($input['id_livraison']) : null;
    $id_commande = isset($input['id_commande']) ? intval($input['id_commande']) : null;

    if (!$paymentMethod || !$id_livraison) {
        throw new Exception('Données manquantes', 400);
    }

    if (!in_array($paymentMethod, ['carte', 'cash'])) {
        throw new Exception('Méthode de paiement invalide', 400);
    }

    // Préparation des données
    $methode = ($paymentMethod === 'carte') ? 'Carte bancaire' : 'Cash';
    $statut = ($paymentMethod === 'carte') ? 'Payé' : 'En attente';
    $datePaiement = date('Y-m-d H:i:s'); // Format datetime pour la base de données
    $dateAffichage = date('d/m/Y H:i:s'); // Format d'affichage pour l'utilisateur

    // Connexion à la base de données
    $pdo = config::getConnexion();
    if (!$pdo) {
        throw new Exception('Impossible de se connecter à la base de données');
    }
    
    // Vérifier que l'ID livraison existe
    $stmtCheck = $pdo->prepare("SELECT id_livraison FROM livraison WHERE id_livraison = ?");
    $stmtCheck->execute([$id_livraison]);
    if (!$stmtCheck->fetch()) {
        throw new Exception("La livraison #$id_livraison n'existe pas");
    }
    
    // Insertion en base
    $stmt = $pdo->prepare("INSERT INTO paiement 
                         (methode_paiement, date_paiement, statut_paiement, id_livraison) 
                         VALUES (:methode, :date, :statut, :id_livraison)");

    if (!$stmt->execute([
        ':methode' => $methode, 
        ':date' => $datePaiement, 
        ':statut' => $statut, 
        ':id_livraison' => $id_livraison
    ])) {
        throw new Exception('Échec de l\'enregistrement en base de données: ' . implode(', ', $stmt->errorInfo()));
    }

    // Récupération du paiement créé
    $lastId = $pdo->lastInsertId();
    
    // Mettre à jour le statut de la commande
    if ($id_commande) {
        $stmtUpdateCommande = $pdo->prepare("UPDATE commande SET statut_commande = 'payée' WHERE id_commande = ?");
        $stmtUpdateCommande->execute([$id_commande]);
    }
    
    // Stockage en session pour affichage
    session_start();
    $_SESSION['paiement_info'] = [
        'id' => $lastId,
        'methode' => $methode,
        'date' => $datePaiement,
        'statut' => $statut,
        'id_livraison' => $id_livraison,
        'id_commande' => $id_commande
    ];

    // Réponse JSON de succès
    sendJsonResponse(true, [
        'method' => $methode,
        'status' => $statut,
        'date' => $dateAffichage,
        'id_livraison' => $id_livraison,
        'id_commande' => $id_commande
    ]);

} catch (PDOException $e) {
    // Erreurs spécifiques PDO
    error_log('Erreur PDO: '.$e->getMessage().' - Code: '.$e->getCode());
    sendJsonResponse(false, 'Erreur de base de données: '.$e->getMessage(), 500);
    
} catch (Exception $e) {
    // Autres exceptions
    error_log('['.date('Y-m-d H:i:s').'] '.$e->getMessage());
    sendJsonResponse(false, $e->getMessage(), $e->getCode() ?: 500);
}
?>
