<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
$db = Config::getConnexion();

try {
    if (!isset($_GET['id'])) {
        throw new Exception("ID de livraison manquant");
    }

    $sql = "SELECT * FROM livraison WHERE id_livraison = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $_GET['id']]);
    $livraison = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$livraison) {
        throw new Exception("Livraison non trouvée");
    }

    echo json_encode($livraison);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>