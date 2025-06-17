<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
$db = Config::getConnexion();

try {
    if (!isset($_GET['id'])) {
        throw new Exception("ID de commande manquant");
    }

    $sql = "SELECT * FROM commande WHERE id_commande = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $_GET['id']]);
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$commande) {
        throw new Exception("Commande non trouvée");
    }

    echo json_encode($commande);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>