<?php
require_once __DIR__ . '/../config.php';
$db = Config::getConnexion();

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Méthode non autorisée", 405);
    }

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data || empty($data['code_promo'])) {
        throw new Exception("Code promo manquant");
    }

    $stmt = $db->prepare("SELECT * FROM promo WHERE code_promo = :code AND id_commande IS NULL");
    $stmt->execute([':code' => $data['code_promo']]);
    $promo = $stmt->fetch();

    if (!$promo) {
        throw new Exception("Code promo invalide ou déjà utilisé");
    }

    echo json_encode([
        'success' => true,
        'reduction' => $promo['reduction'],
        'message' => 'Code promo valide'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>