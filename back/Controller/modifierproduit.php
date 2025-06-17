<?php
require_once '../Controller/produitc.php';
require_once '../Model/produit.php';
session_start();

$controller = new ProduitController();

if (isset($_GET['id_produit'])) {
    $id_produit = $_GET['id_produit'];

    // Récupérer les données du produit à modifier
    $produits = $controller->getAllProduits();
    $produit = null;

    foreach ($produits as $prod) {
        if ($prod['id_produit'] == $id_produit) {
            $produit = $prod;
            break;
        }
    }

    if (!$produit) {
        die("Produit introuvable.");
    }
} else {
    die("ID du produit manquant.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 if (
        isset(
            $_POST['titre_produit'],
            $_POST['prix_produit'],
            $_POST['stock_disponible_produit'],
            $_POST['image_produit'],
            $_POST['genre_produit']
        )
    ) {

    $titre_produit = $_POST['titre_produit'];
    $prix_produit = $_POST['prix_produit'];
    $stock_disponible_produit = $_POST['stock_disponible_produit'];
    $image_produit = $_POST['image_produit'];
    $genre_produit = $_POST['genre_produit'];

    // Création de l'objet Produit avec les bons champs
    $produitObj = new Produit(
        $id_produit,
        $titre_produit,
        $prix_produit,
        $stock_disponible_produit,
        $image_produit,
        $genre_produit
    );

    // Modifier le produit
    $controller->modifierProduit($produitObj);

    // Redirection après modification
    header('Location: ../View/produit.php');
    exit();
} else {
        echo "Erreur : Données manquantes.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit</title>
</head>
<body>
    <h1>Modifier le Produit</h1>
    <form action="" method="POST">
        <label for="titre_produit">Titre Produit :</label>
        <input type="text" id="titre_produit" name="titre_produit" value="<?= htmlspecialchars($produit['titre_produit']) ?>" required><br>

        <label for="genre_produit">Genre Produit :</label>
        <input type="text" id="genre_produit" name="genre_produit" value="<?= htmlspecialchars($produit['genre_produit']) ?>" required><br>

        <label for="stock_disponible_produit">Stock Disponible :</label>
        <input type="number" id="stock_disponible_produit" name="stock_disponible_produit" value="<?= htmlspecialchars($produit['stock_disponible_produit']) ?>" required><br>

        <label for="prix_produit">Prix Produit :</label>
        <input type="number" step="0.01" id="prix_produit" name="prix_produit" value="<?= htmlspecialchars($produit['prix_produit']) ?>" required><br>

        <label for="image_produit">Image Produit :</label>
        <input type="text" id="image_produit" name="image_produit" value="<?= htmlspecialchars($produit['image_produit']) ?>" required><br>

        <button type="submit">Modifier</button>
    </form>
</body>
</html>
