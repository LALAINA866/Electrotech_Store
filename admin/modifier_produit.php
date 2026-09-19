<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Produit.php';
require_once '../classes/Categorie.php';
require_once '../classes/Fournisseur.php';

$produit     = new Produit($pdo);
$categorie   = new Categorie($pdo);
$fournisseur = new Fournisseur($pdo);
$message = "";

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: produits.php'); exit; }

$p = $produit->trouver($id);
if (!$p) { header('Location: produits.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom            = trim($_POST['nom']);
    $description    = trim($_POST['description']);
    $categorie_id   = $_POST['categorie_id'];
    $fournisseur_id = $_POST['fournisseur_id'] ?: null;
    $prix_detail    = $_POST['prix_detail'];
    $prix_gros      = $_POST['prix_gros'];
    $quantite_stock = $_POST['quantite_stock'];
    $seuil_alerte   = $_POST['seuil_alerte'];
    $image          = $p['image']; // on garde l'ancienne image par défaut

    // Si une NOUVELLE image est envoyée, on la remplace
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $nomFichier = time() . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $nomFichier)) {
            $image = $nomFichier;
        }
    }

    if ($nom !== "" && $categorie_id !== "") {
        $produit->modifier($id, $nom, $description, $categorie_id, $fournisseur_id,
                          $prix_detail, $prix_gros, $quantite_stock, $seuil_alerte, $image);
        header('Location: produits.php');
        exit;
    } else {
        $message = "Le nom et la catégorie sont obligatoires.";
    }
}

$listeCategories   = $categorie->lister();
$listeFournisseurs = $fournisseur->lister();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un produit</title>
</head>
<body>
    <h1>Modifier le produit</h1>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="modifier_produit.php?id=<?= $p['id'] ?>" enctype="multipart/form-data">
        <p><label>Nom : <input type="text" name="nom" value="<?= htmlspecialchars($p['nom']) ?>"></label></p>
        <p><label>Description : <input type="text" name="description" value="<?= htmlspecialchars($p['description']) ?>"></label></p>

        <p><label>Catégorie :
            <select name="categorie_id">
                <?php foreach ($listeCategories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $p['categorie_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label></p>

        <p><label>Fournisseur :
            <select name="fournisseur_id">
                <option value="">-- Aucun --</option>
                <?php foreach ($listeFournisseurs as $f): ?>
                    <option value="<?= $f['id'] ?>" <?= $f['id'] == $p['fournisseur_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label></p>

        <p><label>Prix détail : <input type="number" step="0.01" name="prix_detail" value="<?= htmlspecialchars($p['prix_detail']) ?>"></label></p>
        <p><label>Prix gros : <input type="number" step="0.01" name="prix_gros" value="<?= htmlspecialchars($p['prix_gros']) ?>"></label></p>
        <p><label>Quantité en stock : <input type="number" name="quantite_stock" value="<?= htmlspecialchars($p['quantite_stock']) ?>"></label></p>
        <p><label>Seuil d'alerte : <input type="number" name="seuil_alerte" value="<?= htmlspecialchars($p['seuil_alerte']) ?>"></label></p>

        <p>
            Image actuelle :
            <?php if ($p['image']): ?>
                <img src="../uploads/<?= htmlspecialchars($p['image']) ?>" width="50">
            <?php else: ?>
                aucune
            <?php endif; ?>
        </p>
        <p><label>Changer l'image (optionnel) : <input type="file" name="image" accept="image/*"></label></p>

        <p><button type="submit">Enregistrer</button></p>
    </form>
    <p><a href="produits.php">Annuler</a></p>
</body>
</html>