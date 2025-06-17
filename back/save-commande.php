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

$resume_articles = $data['resume_articles'] ?? null;
$statut_commande = $data['statut_commande'] ?? null;
$montant_total = $data['montant_total'] ?? null;

if (!$resume_articles || !$statut_commande || !$montant_total) {
    http_response_code(400);
    echo json_encode(['error' => 'Champs manquants']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO commande (date_commande, resume_articles, statut_commande, montant_total) VALUES (NOW(), ?, ?, ?)");
    $success = $stmt->execute([$resume_articles, $statut_commande, $montant_total]);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur insertion']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur SQL : ' . $e->getMessage()]);
}
