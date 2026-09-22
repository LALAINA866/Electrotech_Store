<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Commande.php';
require_once '../classes/User.php';
require_once '../classes/Produit.php';

$commande = new Commande($pdo);
$user     = new User($pdo);
$produit  = new Produit($pdo);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id  = $_POST['client_id'];
    $type_vente = $_POST['type_vente'];
    $produits   = $_POST['produit_id'];
    $quantites  = $_POST['quantite'];
    $prix       = $_POST['prix_unitaire'];

    if ($client_id === "") {
        $message = "Veuillez choisir un client.";
    } else {
        $resultat = $commande->enregistrer($client_id, $type_vente, $produits, $quantites, $prix);
        if ($resultat === true) {
            $message = "Vente enregistrée et stock mis à jour !";
        } elseif ($resultat === "stock_insuffisant") {
            $message = "Stock insuffisant pour un ou plusieurs produits. Vente annulée.";
        } else {
            $message = "Erreur lors de l'enregistrement.";
        }
    }
}

$listeCommandes = $commande->lister();
$listeClients   = $user->listerClients();  
$listeProduits  = $produit->lister();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des ventes</title>
</head>
<body>
    <h1>Enregistrer une vente</h1>
    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="commandes.php">
        <p><label>Client :
            <select name="client_id">
                <option value="">-- Choisir --</option>
                <?php foreach ($listeClients as $cl): ?>
                    <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </label></p>

        <p><label>Type de vente :
            <select name="type_vente">
                <option value="detail">Détail</option>
                <option value="gros">Gros</option>
            </select>
        </label></p>

        <h3>Produits vendus</h3>
        <table border="1" cellpadding="6">
            <thead>
                <tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th></th></tr>
            </thead>
            <tbody id="lignes">
                <tr>
                    <td>
                        <select name="produit_id[]">
                            <option value="">-- Choisir --</option>
                            <?php foreach ($listeProduits as $p): ?>
                                <option value="<?= $p['id'] ?>">
                                    <?= htmlspecialchars($p['nom']) ?> (stock : <?= $p['quantite_stock'] ?>)
                                </option>
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

        <p><button type="submit">Enregistrer la vente</button></p>
    </form>

    <h2>Historique des ventes</h2>
    <table border="1" cellpadding="6">
        <tr><th>Date</th><th>Client</th><th>Type</th><th>Montant total</th><th>Détail</th></tr>
        <?php foreach ($listeCommandes as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['date_commande']) ?></td>
                <td><?= htmlspecialchars($c['client_nom']) ?></td>
                <td><?= htmlspecialchars($c['type_vente']) ?></td>
                <td><?= htmlspecialchars($c['montant_total']) ?></td>
                <td><a href="detail_commande.php?id=<?= $c['id'] ?>">Voir</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function ajouterLigne() {
            const tbody = document.getElementById('lignes');
            const nouvelle = tbody.rows[0].cloneNode(true);
            nouvelle.querySelectorAll('input').forEach(i => {
                i.value = (i.name === 'quantite[]') ? '1' : '0';
            });
            nouvelle.querySelector('select').selectedIndex = 0;
            tbody.appendChild(nouvelle);
        }
        function supprimerLigne(btn) {
            const tbody = document.getElementById('lignes');
            if (tbody.rows.length > 1) {
                btn.closest('tr').remove();
            }
        }
    </script>
</body>
</html>