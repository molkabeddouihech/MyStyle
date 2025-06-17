<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=site_angular;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

$date_livraison = $data['date_livraison'] ?? null;
$statut_livraison = $data['statut_livraison'] ?? null;
$adresse_livraison = $data['adresse_livraison'] ?? null;
$id_commande = $data['id_commande'] ?? null;

if (!$date_livraison || !$statut_livraison || !$adresse_livraison || !$id_commande) {
    http_response_code(400);
    echo json_encode(['error' => 'Champs manquants']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO livraison (date_livraison, statut_livraison, adresse_livraison, id_commande) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([$date_livraison, $statut_livraison, $adresse_livraison, $id_commande]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Livraison enregistrée avec succès']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur insertion']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur SQL : ' . $e->getMessage()]);
}
?>