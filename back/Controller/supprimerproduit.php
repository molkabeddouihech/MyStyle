<?php
require_once '../Controller/produitc.php';

if (isset($_GET['id_produit'])) {
    $id_produit = $_GET['id_produit'];

    // Instancier le contrôleur
    $controller = new ProduitController();

    // Supprimer le produit
    $controller->supprimerProduit($id_produit);

    // Rediriger vers la page des produits
    header('Location: ../View/produit.php');
    exit();
} else {
    echo "Erreur : ID du produit manquant.";
}
?>
