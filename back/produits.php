<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");



if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Debugging (à retirer en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config.php'; // ✅ Correction du chemin

try {
    $pdo = config::getConnexion(); // Assure-toi que la classe config existe bien dans config.php
    $stmt = $pdo->prepare("SELECT 
        id_produit AS id, 
        titre_produit AS titre, 
        prix_produit AS prix, 
        stock_disponible_produit AS stock, 
        image_produit AS image, 
        genre_produit AS genre 
        FROM produits");
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($produits);
} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
