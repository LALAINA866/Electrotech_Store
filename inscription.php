<?php
require_once 'config/database.php';
require_once 'classes/User.php';

$message = "";

// Quand le formulaire est envoyé (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom   = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mdp   = $_POST['mot_de_passe'];

    $user = new User($pdo);

    if ($nom === "" || $email === "" || $mdp === "") {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($user->emailExiste($email)) {
        $message = "Cet email est déjà utilisé.";
    } else {
        if ($user->inscrire($nom, $email, $mdp)) {
            $message = "Inscription réussie !";
        } else {
            $message = "Une erreur est survenue.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription — ElectroTech Store</title>
</head>
<body>
    <h1>Créer un compte</h1>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="inscription.php">
        <p><label>Nom : <input type="text" name="nom"></label></p>
        <p><label>Email : <input type="email" name="email"></label></p>
        <p><label>Mot de passe : <input type="password" name="mot_de_passe"></label></p>
        <p><button type="submit">S'inscrire</button></p>
    </form>
</body>
</html>