<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
$pdo = new PDO("mysql:host=localhost;dbname=site_angular;charset=utf8", "root", "");

$stmt = $pdo->query("SELECT * FROM promo");
$promos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($promos);
?>
