<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'site_angular');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erreur connexion BDD : ' . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('php://stderr', print_r($data, true)); // pour logs serveur (si dispo)
echo json_encode(['received' => $data]); exit; // ou temporairement juste pour tester


$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Champs manquants']);
    exit;
}

// Récupère l'utilisateur par email
$stmt = $conn->prepare("SELECT mdp FROM utilisateur WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $hash = $row['mdp'];

    // Vérifie le mot de passe avec password_verify
    if (password_verify($password, $hash)) {
        echo json_encode(['success' => true, 'message' => 'Connexion réussie']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Mail introuvable, inscrivez-vous.']);
}

$stmt->close();
$conn->close();
?>
