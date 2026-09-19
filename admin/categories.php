<?php
require_once '../includes/auth.php';
exigerAdmin(); // page réservée à l'administrateur

require_once '../config/database.php';
require_once '../classes/Categorie.php';

$categorie = new Categorie($pdo);
$message = "";

// Traitement de l'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);

    if ($nom === "") {
        $message = "Le nom est obligatoire.";
    } else {
        $categorie->ajouter($nom, $description);
        $message = "Catégorie ajoutée avec succès !";
    }
}

// On récupère la liste (après un éventuel ajout, pour l'afficher à jour)
$liste = $categorie->lister();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des catégories</title>
</head>
<body>
    <h1>Gestion des catégories</h1>
    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <h2>Ajouter une catégorie</h2>
    <form method="POST" action="categories.php">
        <p><label>Nom : <input type="text" name="nom"></label></p>
        <p><label>Description : <input type="text" name="description"></label></p>
        <p><button type="submit">Ajouter</button></p>
    </form>

    <h2>Liste des catégories</h2>
    <table border="1" cellpadding="6">
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($liste as $cat): ?>
            <tr>
                <td><?= htmlspecialchars($cat['nom']) ?></td>
                <td><?= htmlspecialchars($cat['description']) ?></td>
                <td>
                    <a href="modifier_categorie.php?id=<?= $cat['id'] ?>">Modifier</a>
                    <a href="supprimer_categorie.php?id=<?= $cat['id'] ?>"
                       onclick="return confirm('Supprimer cette catégorie ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>