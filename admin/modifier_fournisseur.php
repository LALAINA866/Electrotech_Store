<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Fournisseur.php';

$fournisseur = new Fournisseur($pdo);
$message = "";

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: fournisseurs.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = trim($_POST['nom']);
    $email     = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse   = trim($_POST['adresse']);

    if ($nom !== "") {
        $fournisseur->modifier($id, $nom, $email, $telephone, $adresse);
        header('Location: fournisseurs.php');
        exit;
    } else {
        $message = "Le nom est obligatoire.";
    }
}

$f = $fournisseur->trouver($id);
if (!$f) {
    header('Location: fournisseurs.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un fournisseur</title>
</head>
<body>
    <h1>Modifier le fournisseur</h1>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="modifier_fournisseur.php?id=<?= $f['id'] ?>">
        <p><label>Nom : <input type="text" name="nom" value="<?= htmlspecialchars($f['nom']) ?>"></label></p>
        <p><label>Email : <input type="email" name="email" value="<?= htmlspecialchars($f['email']) ?>"></label></p>
        <p><label>Téléphone : <input type="text" name="telephone" value="<?= htmlspecialchars($f['telephone']) ?>"></label></p>
        <p><label>Adresse : <input type="text" name="adresse" value="<?= htmlspecialchars($f['adresse']) ?>"></label></p>
        <p><button type="submit">Enregistrer</button></p>
    </form>
    <p><a href="fournisseurs.php">Annuler</a></p>
</body>
</html>