<?php
require_once __DIR__ . '/../config.php';
$db = Config::getConnexion();

header('Content-Type: application/json');

function logError($message) {
    file_put_contents(__DIR__.'/command_errors.log', date('Y-m-d H:i:s').' - '.$message.PHP_EOL, FILE_APPEND);
}

try {
    // Vérification méthode POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Méthode non autorisée", 405);
    }

    // Récupération des données
    $input = file_get_contents('php://input');
    if (empty($input)) {
        throw new Exception("Aucune donnée reçue");
    }

    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Données JSON invalides: ".json_last_error_msg());
    }

    // Validation des données obligatoires
    $requiredFields = ['date_commande', 'resume_articles', 'statut_commande', 'montant_total'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("Champ requis manquant: $field");
        }
    }

    // Validation des types de données
    if (!is_numeric($data['montant_total']) || $data['montant_total'] <= 0) {
        throw new Exception("Montant total doit être un nombre positif");
    }

    // Début de la transaction
    $db->beginTransaction();

    try {
        // 1. Insertion dans la table commande
        $stmt = $db->prepare("INSERT INTO commande 
                            (date_commande, resume_articles, statut_commande, montant_total) 
                            VALUES 
                            (:date, :articles, :statut, :montant)");

        $params = [
            ':date' => $data['date_commande'],
            ':articles' => $data['resume_articles'],
            ':statut' => $data['statut_commande'],
            ':montant' => $data['montant_total']
        ];

        if (!$stmt->execute($params)) {
            throw new Exception("Échec de l'insertion dans la table commande");
        }

        // 2. Récupération de l'ID de la commande
        $id_commande = $db->lastInsertId();
        if (!$id_commande) {
            throw new Exception("Impossible de récupérer l'ID de la commande");
        }

        // 3. Mise à jour du stock des produits
        // Analyser le résumé des articles pour extraire les produits et quantités
        $lignes = explode("\n", $data['resume_articles']);
        $produitsCommandes = [];

        foreach ($lignes as $ligne) {
            // Format attendu: "Nom du produit - X x XX.XX DT"
            if (preg_match('/(.+) - (\d+) x /', $ligne, $matches)) {
                $nomProduit = trim($matches[1]);
                $quantite = intval($matches[2]);
                
                // Rechercher l'ID du produit par son nom
                $stmtProduit = $db->prepare("SELECT id_produit, stock_disponible FROM produit WHERE nom_produit = :nom");
                $stmtProduit->execute([':nom' => $nomProduit]);
                $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);
                
                if ($produit) {
                    // Vérifier si le stock est suffisant
                    if ($produit['stock_disponible'] < $quantite) {
                        throw new Exception("Stock insuffisant pour le produit: " . $nomProduit);
                    }
                    
                    // Mettre à jour le stock
                    $stmtUpdateStock = $db->prepare("UPDATE produit SET stock_disponible = stock_disponible - :quantite WHERE id_produit = :id");
                    $stmtUpdateStock->execute([
                        ':quantite' => $quantite,
                        ':id' => $produit['id_produit']
                    ]);
                    
                    if ($stmtUpdateStock->rowCount() === 0) {
                        throw new Exception("Échec de la mise à jour du stock pour: " . $nomProduit);
                    }
                    
                    // Enregistrer le produit commandé pour le récapitulatif
                    $produitsCommandes[] = [
                        'id' => $produit['id_produit'],
                        'nom' => $nomProduit,
                        'quantite' => $quantite
                    ];
                } else {
                    // Produit non trouvé, on continue sans erreur mais on log
                    logError("Produit non trouvé dans la base de données: " . $nomProduit);
                }
            }
        }

        // 4. Gestion du code promo si fourni
        if (!empty($data['code_promo'])) {
            // Vérification du code promo
            $stmt = $db->prepare("SELECT id_promo, reduction FROM promo 
                                WHERE code_promo = :code AND id_commande IS NULL 
                                LIMIT 1 FOR UPDATE"); // FOR UPDATE pour verrouiller la ligne
            
            $stmt->execute([':code' => $data['code_promo']]);
            $promo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$promo) {
                throw new Exception("Code promo invalide ou déjà utilisé");
            }

            // Mise à jour du code promo
            $stmt = $db->prepare("UPDATE promo SET id_commande = :id_commande 
                                WHERE id_promo = :id_promo");
            
            $updated = $stmt->execute([
                ':id_commande' => $id_commande,
                ':id_promo' => $promo['id_promo']
            ]);

            if (!$updated || $stmt->rowCount() === 0) {
                throw new Exception("Échec de la mise à jour du code promo");
            }

            $data['reduction'] = $promo['reduction'];
        }

        // Validation de la transaction
        $db->commit();

        // Réponse JSON
        $response = [
            'success' => true,
            'message' => 'Commande enregistrée avec succès',
            'commande_id' => $id_commande,
            'produits_commandes' => $produitsCommandes,
            'commande_data' => [
                'date' => $data['date_commande'],
                'articles' => $data['resume_articles'],
                'statut' => $data['statut_commande'],
                'montant' => $data['montant_total'],
                'reduction' => $data['reduction'] ?? 0,
                'nom' => $data['nom_client'] ?? '',
                'prenom' => $data['prenom_client'] ?? '',
                'email' => $data['email_client'] ?? ''
            ]
        ];

        echo json_encode($response);

    } catch (Exception $e) {
        $db->rollBack();
        throw $e; // Relance l'exception pour le catch principal
    }

} catch (Exception $e) {
    logError($e->getMessage());
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_details' => $e->getTraceAsString()
    ]);
}
?>
