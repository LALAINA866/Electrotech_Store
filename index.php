<?php require_once 'config/database.php'; ?>
<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElectroTech Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>ElectroTech Store</h1>
    <p>Votre univers tech, à portée de clic.</p>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- L'utilisateur EST connecté -->
        <p>Bonjour <?= htmlspecialchars($_SESSION['user_nom']) ?> !
           (<?= htmlspecialchars($_SESSION['user_role']) ?>)</p>
        <p><a href="deconnexion.php">Se déconnecter</a></p>
    <?php else: ?>
        <!-- L'utilisateur N'EST PAS connecté -->
        <p><a href="connexion.php">Se connecter</a> |
           <a href="inscription.php">Créer un compte</a></p>
    <?php endif; ?>
</body>
</html>
