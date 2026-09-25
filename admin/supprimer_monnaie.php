<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Monnaie.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $monnaie = new Monnaie($pdo);
    $monnaie->supprimer($id);
}
header('Location: monnaies.php');
exit;
?>