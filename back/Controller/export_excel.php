<?php
session_start();
require_once '../Model/produit.php';
require_once 'produitc.php';

// Utilisez le contrôleur existant
$controller = new ProduitController();
$produits = $controller->getAllProduits();

// Appelez la méthode d'export existante
$controller->exportToExcel($produits);
?>