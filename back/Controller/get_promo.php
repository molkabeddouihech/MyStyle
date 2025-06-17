<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
$db = Config::getConnexion();

try {
    if (!isset($_GET['id'])) {
        throw new Exception("ID de promotion manquant");
    }

    $sql = "SELECT * FROM promo WHERE id_promo = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $_GET['id']]);
    $promo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$promo) {
        throw new Exception("Promotion non trouvée");
    }

    echo json_encode($promo);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>