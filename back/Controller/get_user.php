<?php
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Autorise toutes les origines pour éviter les erreurs CORS
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

require_once __DIR__ . '/../config.php';

try {
    if (!isset($_GET['email']) || empty($_GET['email'])) {
        throw new Exception("Email manquant");
    }

    $db = Config::getConnexion();

    $sql = "SELECT * FROM utilisateur WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute([':email' => $_GET['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Utilisateur non trouvé");
    }

    // Ne jamais retourner le mot de passe hashé
    unset($user['mdp']);

    echo json_encode([
        'success' => true,
        'user' => $user
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}