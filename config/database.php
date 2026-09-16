<?php
/**
 * Connexion à la base de données MySQL via PDO.
 * Ce fichier est inclus par toutes les pages qui ont besoin de la base.
 * Projet : ElectroTech Store
 */

$hote        = 'localhost';
$base        = 'electrotech_store';
$utilisateur = 'root';   // utilisateur MySQL par défaut sous XAMPP
$motdepasse  = '';       // mot de passe vide par défaut sous XAMPP

try {
    $pdo = new PDO(
        "mysql:host=$hote;dbname=$base;charset=utf8mb4",
        $utilisateur,
        $motdepasse
    );
    // Afficher les erreurs SQL sous forme d'exceptions (utile pendant le développement)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Récupérer les résultats sous forme de tableaux associatifs
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
