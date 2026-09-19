<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Categorie.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $categorie = new Categorie($pdo);
    $categorie->supprimer($id);
}
header('Location: categories.php'); // retour à la liste
exit;
?>