<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Categorie.php';

$categorie = new Categorie($pdo);
$message = "";

// On récupère l'id passé dans l'URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: categories.php');
    exit;
}

// Traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    if ($nom !== "") {
        $categorie->modifier($id, $nom, $description);
        header('Location: categories.php'); // retour à la liste
        exit;
    } else {
        $message = "Le nom est obligatoire.";
    }
}

// On charge la catégorie actuelle pour pré-remplir le formulaire
$cat = $categorie->trouver($id);
if (!$cat) {
    header('Location: categories.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une catégorie</title>
</head>
<body>
    <h1>Modifier la catégorie</h1>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="modifier_categorie.php?id=<?= $cat['id'] ?>">
        <p><label>Nom : <input type="text" name="nom" value="<?= htmlspecialchars($cat['nom']) ?>"></label></p>
        <p><label>Description : <input type="text" name="description" value="<?= htmlspecialchars($cat['description']) ?>"></label></p>
        <p><button type="submit">Enregistrer</button></p>
    </form>
    <p><a href="categories.php">Annuler</a></p>
</body>
</html>