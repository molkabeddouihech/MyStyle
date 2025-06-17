<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
$db = Config::getConnexion();

try {
    if (!isset($_GET['id'])) {
        throw new Exception("ID de paiement manquant");
    }

    $sql = "SELECT * FROM paiement WHERE id_paiement = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $_GET['id']]);
    $paiement = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$paiement) {
        throw new Exception("Paiement non trouvé");
    }

    echo json_encode($paiement);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>