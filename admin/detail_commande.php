<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Commande.php';
require_once '../classes/Monnaie.php';

$commande = new Commande($pdo);
$monnaie = new Monnaie($pdo);

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: commandes.php'); exit; }

$entete = $commande->trouver($id);
if (!$entete) { header('Location: commandes.php'); exit; }

$lignes = $commande->details($id);
$listeMonnaies = $monnaie->lister();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de la vente</title>
</head>
<body>
    <h1>Détail de la vente n°<?= htmlspecialchars($entete['id']) ?></h1>
    <p><a href="commandes.php">← Retour aux ventes</a></p>
    <h3>Générer la facture</h3>
    <form method="GET" action="facture_pdf.php" target="_blank">
        <input type="hidden" name="id" value="<?= $entete['id'] ?>">
            <label>Monnaie :
                <select name="monnaie_id">
                    <?php foreach ($listeMonnaies as $m): ?>
                        <option value="<?= $m['id'] ?>">
                            <?= htmlspecialchars($m['nom']) ?> (<?= htmlspecialchars($m['symbole']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit">Générer la facture (PDF)</button>
    </form>

    <p>
        <strong>Client :</strong> <?= htmlspecialchars($entete['client_nom']) ?><br>
        <strong>Date :</strong> <?= htmlspecialchars($entete['date_commande']) ?><br>
        <strong>Type :</strong> <?= htmlspecialchars($entete['type_vente']) ?><br>
        <strong>Montant total :</strong> <?= htmlspecialchars($entete['montant_total']) ?>
    </p>

    <h2>Produits</h2>
    <table border="1" cellpadding="6">
        <tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Sous-total</th></tr>
        <?php foreach ($lignes as $l): ?>
            <tr>
                <td><?= htmlspecialchars($l['produit_nom']) ?></td>
                <td><?= htmlspecialchars($l['quantite']) ?></td>
                <td><?= htmlspecialchars($l['prix_unitaire']) ?></td>
                <td><?= htmlspecialchars($l['quantite'] * $l['prix_unitaire']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>