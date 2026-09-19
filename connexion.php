<?php
session_start(); // Démarre la session — TOUJOURS en première ligne
require_once 'config/database.php';
require_once 'classes/User.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mdp   = $_POST['mot_de_passe'];

    $user = new User($pdo);
    $utilisateur = $user->connecter($email, $mdp);

    if ($utilisateur) {
        // Connexion réussie : on enregistre les infos dans la session
        $_SESSION['user_id']   = $utilisateur['id'];
        $_SESSION['user_nom']  = $utilisateur['nom'];
        $_SESSION['user_role'] = $utilisateur['role'];

        // Redirection selon le rôle
        if ($utilisateur['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: index.php');
        }
        exit; // important : on arrête le script après une redirection
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion — ElectroTech Store</title>
</head>
<body>
    <h1>Se connecter</h1>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="connexion.php">
        <p><label>Email : <input type="email" name="email"></label></p>
        <p><label>Mot de passe : <input type="password" name="mot_de_passe"></label></p>
        <p><button type="submit">Se connecter</button></p>
    </form>
</body>
</html>