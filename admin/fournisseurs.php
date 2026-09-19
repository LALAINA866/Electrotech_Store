<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Fournisseur.php';

$fournisseur = new Fournisseur($pdo);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = trim($_POST['nom']);
    $email     = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse   = trim($_POST['adresse']);

    if ($nom === "") {
        $message = "Le nom est obligatoire.";
    } else {
        $fournisseur->ajouter($nom, $email, $telephone, $adresse);
        $message = "Fournisseur ajouté avec succès !";
    }
}

$liste = $fournisseur->lister();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des fournisseurs</title>
</head>
<body>
    <h1>Gestion des fournisseurs</h1>
    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <h2>Ajouter un fournisseur</h2>
    <form method="POST" action="fournisseurs.php">
        <p><label>Nom : <input type="text" name="nom"></label></p>
        <p><label>Email : <input type="email" name="email"></label></p>
        <p><label>Téléphone : <input type="text" name="telephone"></label></p>
        <p><label>Adresse : <input type="text" name="adresse"></label></p>
        <p><button type="submit">Ajouter</button></p>
    </form>

    <h2>Liste des fournisseurs</h2>
    <table border="1" cellpadding="6">
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Adresse</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($liste as $f): ?>
            <tr>
                <td><?= htmlspecialchars($f['nom']) ?></td>
                <td><?= htmlspecialchars($f['email']) ?></td>
                <td><?= htmlspecialchars($f['telephone']) ?></td>
                <td><?= htmlspecialchars($f['adresse']) ?></td>
                <td>
                    <a href="modifier_fournisseur.php?id=<?= $f['id'] ?>">Modifier</a>
                    <a href="supprimer_fournisseur.php?id=<?= $f['id'] ?>"
                       onclick="return confirm('Supprimer ce fournisseur ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>