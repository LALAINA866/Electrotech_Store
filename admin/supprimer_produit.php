<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Produit.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $produit = new Produit($pdo);
    $produit->supprimer($id);
}
header('Location: produits.php');
exit;
?>