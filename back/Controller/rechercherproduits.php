<?php
include '../../config.php';

$titre = $_GET['titre'] ?? '';
$genre = $_GET['genre'] ?? 'all';

$sql = "SELECT * FROM produits WHERE 1=1";

if (!empty($titre)) {
    $titre = $conn->real_escape_string($titre);
    $sql .= " AND titre_produit LIKE '%$titre%'";
}

if ($genre !== 'all') {
    $genre = $conn->real_escape_string($genre);
    $sql .= " AND genre_produit = '$genre'";
}

$result = $conn->query($sql);

$produits = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produits[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($produits);
?>
