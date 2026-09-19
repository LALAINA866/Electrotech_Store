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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom            = trim($_POST['nom']);
    $description    = trim($_POST['description']);
    $categorie_id   = $_POST['categorie_id'];
    $fournisseur_id = $_POST['fournisseur_id'] ?: null;
    $prix_detail    = $_POST['prix_detail'];
    $prix_gros      = $_POST['prix_gros'];
    $quantite_stock = $_POST['quantite_stock'];
    $seuil_alerte   = $_POST['seuil_alerte'];
    $image          = null;

    // Gestion de l'upload de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $dossier = '../uploads/';
        // On crée un nom unique pour éviter d'écraser une image existante
        $nomFichier = time() . '_' . basename($_FILES['image']['name']);
        $destination = $dossier . $nomFichier;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $image = $nomFichier; // on stocke seulement le nom dans la base
        }
    }

    if ($nom === "" || $categorie_id === "") {
        $message = "Le nom et la catégorie sont obligatoires.";
    } else {
        $produit->ajouter($nom, $description, $categorie_id, $fournisseur_id,
                          $prix_detail, $prix_gros, $quantite_stock, $seuil_alerte, $image);
        $message = "Produit ajouté avec succès !";
    }
}

// On récupère le terme de recherche et categorie (vide s'il n'y en a pas)
$recherche = trim($_GET['recherche'] ?? '');
$categorie_id = $_GET['categorie'] ?? '';

// Si l'admin a tapé quelque chose → on recherche ; sinon → on liste tout
if ($recherche !== '' || $categorie_id !== '') {
    $liste = $produit->rechercher($recherche, $categorie_id);
} else {
    $liste = $produit->lister();
}
$listeCategories = $categorie->lister();
$listeFournisseurs = $fournisseur->lister();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des produits</title>
</head>
<body>
    <h1>Gestion des produits</h1>
    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <h2>Ajouter un produit</h2>
    <!-- enctype OBLIGATOIRE pour envoyer un fichier -->
    <form method="POST" action="produits.php" enctype="multipart/form-data">
        <p><label>Nom : <input type="text" name="nom"></label></p>
        <p><label>Description : <input type="text" name="description"></label></p>

        <p><label>Catégorie :
            <select name="categorie_id">
                <option value="">-- Choisir --</option>
                <?php foreach ($listeCategories as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </label></p>

        <p><label>Fournisseur :
            <select name="fournisseur_id">
                <option value="">-- Aucun --</option>
                <?php foreach ($listeFournisseurs as $f): ?>
                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </label></p>

        <p><label>Prix détail : <input type="number" step="0.01" name="prix_detail" value="0"></label></p>
        <p><label>Prix gros : <input type="number" step="0.01" name="prix_gros" value="0"></label></p>
        <p><label>Quantité en stock : <input type="number" name="quantite_stock" value="0"></label></p>
        <p><label>Seuil d'alerte : <input type="number" name="seuil_alerte" value="5"></label></p>
        <p><label>Image : <input type="file" name="image" accept="image/*"></label></p>
        <p><button type="submit">Ajouter</button></p>
    </form>

    <h2>Rechercher un produit</h2>
        <form method="GET" action="produits.php">
              <input type="text" name="recherche" placeholder="Nom du produit" value="<?= htmlspecialchars($recherche ?? '') ?>">
              <button type="submit">Rechercher</button>
              <a href="produits.php">Réinitialiser</a>
              <select name="categorie">
                 <option value="">Toutes les catégories</option>
                 <?php foreach ($listeCategories as $c): ?>
                   <option value="<?= $c['id'] ?>"
                      <?= $c['id'] == $categorie_id ? 'selected' : '' ?>>
                      <?= htmlspecialchars($c['nom']) ?>
                   </option>
                 <?php endforeach; ?>
              </select>
        </form>

    <h2>Liste des produits</h2>
    <table border="1" cellpadding="6">
        <thead>
            <tr>
               <th>Image</th><th>Nom</th><th>Catégorie</th><th>Fournisseur</th>
               <th>Prix détail</th><th>Prix gros</th><th>Stock</th><th>Actions</th>
            </tr>
        </thead>
        
        <tbody id="corps-tableau">
            <?php foreach ($liste as $p): ?>
                <tr>
                    <td>
                        <?php if ($p['image']): ?>
                        <img src="../uploads/<?= htmlspecialchars($p['image']) ?>" width="50">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= htmlspecialchars($p['categorie_nom']) ?></td>
                    <td><?= htmlspecialchars($p['fournisseur_nom']) ?></td>
                    <td><?= htmlspecialchars($p['prix_detail']) ?></td>
                    <td><?= htmlspecialchars($p['prix_gros']) ?></td>
                    <td>
                        <?= htmlspecialchars($p['quantite_stock']) ?>
                        <?php if ($p['quantite_stock'] <= $p['seuil_alerte']): ?>
                        <strong style="color:red;">(stock bas !)</strong>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="modifier_produit.php?id=<?= $p['id'] ?>">Modifier</a>
                        <a href="supprimer_produit.php?id=<?= $p['id'] ?>"
                        onclick="return confirm('Supprimer ce produit ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        
        // 1. On récupère les 3 éléments dont on a besoin
        const champRecherche = document.querySelector('input[name="recherche"]');
        const champCategorie = document.querySelector('select[name="categorie"]');
        const corpsTableau   = document.getElementById('corps-tableau');

        // 2. La fonction qui va chercher les produits sans recharger la page
        function rechercherAjax() {
            const terme = champRecherche.value;
            const cat   = champCategorie.value;

            // On construit l'adresse du fichier de recherche avec les critères
            const url = 'recherche_produits.php?recherche=' + encodeURIComponent(terme)
                  + '&categorie=' + encodeURIComponent(cat);

            // On va chercher les lignes sur le serveur
            fetch(url)
                .then(reponse => reponse.text())   // on récupère la réponse en texte (les <tr>)
                .then(html => {
                    corpsTableau.innerHTML = html;  // on remplace le contenu du tableau
                })
                .catch(erreur => {
                    console.error('Erreur lors de la recherche :', erreur);
                });
        }

        // 3. On déclenche la recherche à chaque frappe et à chaque changement de catégorie
        champRecherche.addEventListener('input', rechercherAjax);
        champCategorie.addEventListener('change', rechercherAjax);
    </script>
</body>
</html>