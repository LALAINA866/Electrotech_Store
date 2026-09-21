<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Achat.php';

$achat = new Achat($pdo);

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: achats.php'); exit; }

$entete = $achat->trouver($id);
if (!$entete) { header('Location: achats.php'); exit; }

$lignes = $achat->details($id);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de l'achat</title>
</head>
<body>
    <h1>Détail de l'achat n°<?= htmlspecialchars($entete['id']) ?></h1>
    <p><a href="achats.php">← Retour aux achats</a></p>

    <p>
        <strong>Fournisseur :</strong> <?= htmlspecialchars($entete['fournisseur_nom']) ?><br>
        <strong>Date :</strong> <?= htmlspecialchars($entete['date_achat']) ?><br>
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