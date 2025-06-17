<?php
require_once '../Model/produit.php';
require_once __DIR__ . '/../Controller/produitc.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ProduitController();

    // Vérifiez si les données sont reçues
    if (
    isset(
        $_POST['id_produit'],
        $_POST['titre_produit'],
        $_POST['prix_produit'],
        $_POST['stock_disponible_produit'],
        $_POST['genre_produit']
    ) && isset($_FILES['image_produit'])
)
     {
    $id_produit = $_POST['id_produit'];  // Peut être vide ou null si ajout
    $titre_produit = $_POST['titre_produit'];
    $prix_produit = $_POST['prix_produit'];
    $stock_disponible_produit = $_POST['stock_disponible_produit'];
    $image_produit = $_FILES['image_produit']['name'];
    $genre_produit = $_POST['genre_produit'];

    // Créer l'objet Produit sans gestion dépôt
    $produit = new Produit(
        $id_produit,
        $titre_produit,
        $prix_produit,
        $stock_disponible_produit,
        $image_produit,
        $genre_produit
    );

    // Vérifiez si le produit existe déjà
        if ($controller->getProduitById($id_produit)) {
            // Modifier le produit existant
            $controller->modifierProduit($produit);
        } else {
            // Ajouter un nouveau produit
            $controller->ajouterProduit($produit);
     }
    // Redirection vers la liste des produits
    header('Location: ../View/produit.php');
         exit();
    } else {
        echo "Erreur : Données manquantes.";
    }
} else {
    echo "Erreur : Requête non valide.";
}
?>