<?php
require_once '../includes/auth.php';
exigerAdmin(); // ← cette seule ligne protège toute la page
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord — Administration</title>
</head>
<body>
    <h1>Espace administrateur</h1>
    <p>Bonjour <?= htmlspecialchars($_SESSION['user_nom']) ?>, vous êtes administrateur.</p>
    <p><a href="../deconnexion.php">Se déconnecter</a></p>
    <p><a href="categories.php">Gérer les catégories</a></p>
    <p><a href="fournisseurs.php">Gérer les fournisseurs</a></p>
    <p><a href="produits.php">Gérer les produits</a></p>
    <p><a href="achats.php">Gérer les achats</a></p>
    <p><a href="commandes.php">Gérer les ventes</a></p>
</body>
</html>