<?php
session_start();
require_once '../../Controller/produitc.php';

$controller = new ProduitController();
$produits = $controller->getAllProduits();

$produitToEdit = null;
if (isset($_GET['id_produit'])) {
    foreach ($produits as $produit) {
        if ($produit['id_produit'] == $_GET['id_produit']) {
            $produitToEdit = $produit;
            break;
        }
    }
}

if (!isset($produitToEdit) && isset($_SESSION['form_data'])) {
    $produitToEdit = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
}

?>
