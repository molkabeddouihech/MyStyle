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

$methode_paiement = $data['methode_paiement'] ?? null;
$statut_paiement = $data['statut_paiement'] ?? null;
$id_livraison = $data['id_livraison'] ?? null;

if (!$methode_paiement || !$statut_paiement || !$id_livraison) {
    http_response_code(400);
    echo json_encode(['error' => 'Champs manquants']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO paiement (methode_paiement, date_paiement, statut_paiement, id_livraison) VALUES (?, NOW(), ?, ?)");
    $success = $stmt->execute([$methode_paiement, $statut_paiement, $id_livraison]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Paiement enregistré avec succès']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur insertion']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur SQL : ' . $e->getMessage()]);
}
?>