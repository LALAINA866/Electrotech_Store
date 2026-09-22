<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Commande.php';

$commande = new Commande($pdo);

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: commandes.php'); exit; }

$entete = $commande->trouver($id);
if (!$entete) { header('Location: commandes.php'); exit; }

$lignes = $commande->details($id);
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