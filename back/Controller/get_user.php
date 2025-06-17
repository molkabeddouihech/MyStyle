<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');

$db = Config::getConnexion();

try {
    if (!isset($_GET['email'])) {
        throw new Exception("Email manquant");
    }

    $sql = "SELECT * FROM utilisateur WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute([':email' => $_GET['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Utilisateur non trouvé");
    }

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
?>
