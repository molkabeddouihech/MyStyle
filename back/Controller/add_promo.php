<?php
require_once __DIR__ . '/../../config.php';
header('Content-Type: application/json');

try {
    // Vérifier la méthode HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Méthode non autorisée", 405);
    }

    // Récupérer les données
    $input = json_decode(file_get_contents('php://input'), true);
    $code_promo = isset($input['code_promo']) ? strtoupper(trim($input['code_promo'])) : null;
    $reduction = isset($input['reduction']) ? $input['reduction'] : null;

    // Validation des données
    if (empty($code_promo) || empty($reduction)) {
        throw new Exception("Tous les champs sont obligatoires", 400);
    }

    if (!preg_match('/^[A-Z0-9]+$/', $code_promo)) {
        throw new Exception("Code promo invalide: uniquement lettres majuscules et chiffres", 400);
    }

    if (!is_numeric($reduction)) {
        throw new Exception("La réduction doit être un nombre valide", 400);
    }

    $reductionValue = (float)$reduction;
    if ($reductionValue <= 0 || $reductionValue > 100) {
        throw new Exception("La réduction doit être entre 0.01 et 100", 400);
    }

    $db = Config::getConnexion();

    // Vérifier l'existence du code
    $check = $db->prepare("SELECT 1 FROM promo WHERE code_promo = ?");
    $check->execute([$code_promo]);
    if ($check->fetch()) {
        throw new Exception("Ce code promo existe déjà", 409);
    }

    // Insertion
    $stmt = $db->prepare("INSERT INTO promo (code_promo, reduction) VALUES (?, ?)");
    $stmt->execute([$code_promo, $reductionValue]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Erreur lors de l'ajout de la promotion", 500);
    }

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Promotion ajoutée avec succès',
        'data' => [
            'id' => $db->lastInsertId(),
            'code_promo' => $code_promo,
            'reduction' => $reductionValue
        ]
    ]);

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode() ?: 500
    ]);
}
?>