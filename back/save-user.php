<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=site_angular;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit;
}

// Récupération des données JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

$nom = $data['nom'] ?? null;
$prenom = $data['prenom'] ?? null;
$email = $data['email'] ?? null;
$mpd = $data['mpd'] ?? null;

if (!$nom || !$prenom || !$email || !$mpd) {
    http_response_code(400);
    echo json_encode(['error' => 'Champs manquants']);
    exit;
}

// Hachage du mot de passe
$hashedPassword = password_hash($mpd, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, mpd, date) VALUES (?, ?, ?, ?, NOW())");
    $success = $stmt->execute([$nom, $prenom, $email, $hashedPassword]);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur d\'insertion']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur SQL : ' . $e->getMessage()]);
}
