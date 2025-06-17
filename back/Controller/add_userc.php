<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

require_once __DIR__ . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            exit;
        }

        $nom = trim($input['nom'] ?? '');
        $prenom = trim($input['prenom'] ?? '');
        $email = trim($input['email'] ?? '');
        $mdp = $input['mdp'] ?? '';
        $date = $input['date'] ?? '';

        $errors = [];

        if (empty($nom)) $errors[] = "Le nom est obligatoire.";
        if (empty($prenom)) $errors[] = "Le prénom est obligatoire.";
        if (empty($email)) $errors[] = "L'email est obligatoire.";
        if (empty($mdp)) $errors[] = "Le mot de passe est obligatoire.";
        if (empty($date)) $errors[] = "La date de naissance est obligatoire.";

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
            exit;
        }

        $db = Config::getConnexion();

        // Vérifier si l'email existe déjà
        $checkSql = "SELECT COUNT(*) FROM utilisateur WHERE email = :email";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => "Ce compte existe déjà. Veuillez vous connecter."]);
            exit;
        }

        // Insérer le nouvel utilisateur
        $sql = "INSERT INTO utilisateur (nom, prenom, email, mdp, date) VALUES (:nom, :prenom, :email, :mdp, :date)";
        $stmt = $db->prepare($sql);

        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mdp', $mdp_hash);
        $stmt->bindParam(':date', $date);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'inscription.']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur système.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>