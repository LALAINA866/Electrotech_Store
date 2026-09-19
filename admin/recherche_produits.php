<?php
require_once '../includes/auth.php';
exigerAdmin();
require_once '../config/database.php';
require_once '../classes/Produit.php';

$produit = new Produit($pdo); 

$recherche = trim($_GET['recherche'] ?? '');
$categorie_id = $_GET['categorie'] ?? '';

if ($recherche !== '' || $categorie_id !== '') {
    $liste = $produit->rechercher($recherche, $categorie_id);
} else {
    $liste = $produit->lister();
}
?>

<?php if (count($liste) === 0): ?>
    <tr><td colspan="8">Aucun produit trouvé.</td></tr>
<?php else: ?>
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
<?php endif; ?>