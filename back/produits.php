<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php'; // Ton fichier de connexion PDO

try {
    $pdo = config::getConnexion();
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