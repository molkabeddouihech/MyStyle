<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');

try {
    // Connexion DB
    $db = Config::getConnexion();

    // Lire les données JSON envoyées
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? null;

    if (!$email) {
        throw new Exception("Email manquant");
    }

    $stmt = $db->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(['exists' => true, 'user' => $user]);
    } else {
        echo json_encode(['exists' => false]);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
