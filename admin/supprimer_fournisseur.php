<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Fournisseur.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $fournisseur = new Fournisseur($pdo);
    $fournisseur->supprimer($id);
}
header('Location: fournisseurs.php');
exit;
?>