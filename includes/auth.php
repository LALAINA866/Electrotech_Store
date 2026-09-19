<?php
session_start();

// Vérifie que l'utilisateur est connecté. Sinon, redirige vers la connexion.
function exigerConnexion()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../connexion.php');
        exit;
    }
}

// Vérifie que l'utilisateur est un administrateur. Sinon, l'éjecte.
function exigerAdmin()
{
    exigerConnexion(); // d'abord, il doit être connecté
    if ($_SESSION['user_role'] !== 'admin') {
        header('Location: ../index.php'); // un client n'a rien à faire ici
        exit;
    }
}
?>