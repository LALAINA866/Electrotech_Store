<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Achat.php';
require_once '../classes/Fournisseur.php';
require_once '../classes/Produit.php';

$achat       = new Achat($pdo);
$fournisseur = new Fournisseur($pdo);
$produit     = new Produit($pdo);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fournisseur_id = $_POST['fournisseur_id'];
    $user_id        = $_SESSION['user_id'];   // l'admin connecté
    $produits       = $_POST['produit_id'];   // tableau (plusieurs lignes)
    $quantites      = $_POST['quantite'];     // tableau
    $prix           = $_POST['prix_unitaire'];// tableau

    if ($fournisseur_id === "") {
        $message = "Veuillez choisir un fournisseur.";
    } else {
        $resultat = $achat->enregistrer($fournisseur_id, $user_id, $produits, $quantites, $prix);
        if ($resultat === true) {
            $message = "Achat enregistré et stock mis à jour !";
        } elseif($resultat === "aucun_produit") {
            $message = "Veuillez ajouter au moins un produit à l'achat.";
        } else{
            $message = "Erreur lors de l'enregistrement.";
        }
    }
}

$listeAchats       = $achat->lister();
$listeFournisseurs = $fournisseur->lister();
$listeProduits     = $produit->lister();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des achats</title>
</head>
<body>
    <h1>Enregistrer un achat</h1>
    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="achats.php">
        <p><label>Fournisseur :
            <select name="fournisseur_id">
                <option value="">-- Choisir --</option>
                <?php foreach ($listeFournisseurs as $f): ?>
                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </label></p>

        <h3>Produits achetés</h3>
        <table border="1" cellpadding="6">
            <thead>
                <tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th></th></tr>
            </thead>
            <tbody id="lignes">
                <!-- La première ligne (modèle) -->
                <tr>
                    <td>
                        <select name="produit_id[]">
                            <option value="">-- Choisir --</option>
                            <?php foreach ($listeProduits as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="number" name="quantite[]" value="1" min="1"></td>
                    <td><input type="number" step="0.01" name="prix_unitaire[]" value="0"></td>
                    <td><button type="button" onclick="supprimerLigne(this)">X</button></td>
                </tr>
            </tbody>
        </table>
        <p><button type="button" onclick="ajouterLigne()">+ Ajouter un produit</button></p>

        <p><button type="submit">Enregistrer l'achat</button></p>
    </form>

    <h2>Historique des achats</h2>
    <table border="1" cellpadding="6">
        <tr><th>Date</th><th>Fournisseur</th><th>Enregistré par</th><th>Montant total</th><th>Détail</th></tr>
        <?php foreach ($listeAchats as $a): ?>
            <tr>
                <td><?= htmlspecialchars($a['date_achat']) ?></td>
                <td><?= htmlspecialchars($a['fournisseur_nom']) ?></td>
                <td><?= htmlspecialchars($a['user_nom']) ?></td>
                <td><?= htmlspecialchars($a['montant_total']) ?></td>
                <td><a href="detail_achat.php?id=<?= $a['id'] ?>">Voir</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        // Ajoute une nouvelle ligne en copiant la première
        function ajouterLigne() {
            const tbody = document.getElementById('lignes');
            const nouvelle = tbody.rows[0].cloneNode(true); // copie de la 1re ligne
            // On remet les champs de la copie à leurs valeurs par défaut
            nouvelle.querySelectorAll('input').forEach(i => {
                i.value = (i.name === 'quantite[]') ? '1' : '0';
            });
            nouvelle.querySelector('select').selectedIndex = 0;
            tbody.appendChild(nouvelle);
        }

        // Supprime une ligne (mais on garde toujours au moins une)
        function supprimerLigne(btn) {
            const tbody = document.getElementById('lignes');
            if (tbody.rows.length > 1) {
                btn.closest('tr').remove();
            }
        }
    </script>
</body>
</html>