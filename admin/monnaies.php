<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Monnaie.php';

$monnaie = new Monnaie($pdo);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = trim($_POST['nom']);
    $symbole = trim($_POST['symbole']);

    if ($nom === "" || $symbole === "") {
        $message = "Le nom et le symbole sont obligatoires.";
    } else {
        $monnaie->ajouter($nom, $symbole);
        $message = "Monnaie ajoutée avec succès !";
    }
}

$liste = $monnaie->lister();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des monnaies</title>
</head>
<body>
    <h1>Gestion des monnaies</h1>
    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <h2>Ajouter une monnaie</h2>
    <form method="POST" action="monnaies.php">
        <p><label>Nom : <input type="text" name="nom" placeholder="Ex : Livre sterling"></label></p>
        <p><label>Symbole : <input type="text" name="symbole" placeholder="Ex : GBP"></label></p>
        <p><button type="submit">Ajouter</button></p>
    </form>

    <h2>Liste des monnaies</h2>
    <table border="1" cellpadding="6">
        <tr><th>Nom</th><th>Symbole</th><th>Actions</th></tr>
        <?php foreach ($liste as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['nom']) ?></td>
                <td><?= htmlspecialchars($m['symbole']) ?></td>
                <td>
                    <a href="supprimer_monnaie.php?id=<?= $m['id'] ?>"
                       onclick="return confirm('Supprimer cette monnaie ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>