<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../config/boutique.php';
require_once '../classes/Monnaie.php';

$monnaie = new Monnaie($pdo);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = trim($_POST['nom']);
    $symbole   = trim($_POST['symbole']);
    $taux      = str_replace(',', '.', trim($_POST['taux']));   // accepte 0,29 ou 0.29
    $decimales = (int) $_POST['decimales'];

    if ($nom === "" || $symbole === "") {
        $message = "Le nom et le symbole sont obligatoires.";
    } elseif (!is_numeric($taux) || $taux <= 0) {
        $message = "Le taux de conversion doit être un nombre supérieur à 0.";
    } elseif ($decimales < 0 || $decimales > 3) {
        $message = "Le nombre de décimales doit être compris entre 0 et 3.";
    } else {
        $monnaie->ajouter($nom, $symbole, $taux, $decimales);
        $message = "Monnaie ajoutée avec succès !";
    }
}

$liste = $monnaie->lister();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des monnaies</title>
</head>

<body>
    <h1>Gestion des monnaies</h1>
    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

    <?php if ($message !== ""): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <h2>Ajouter une monnaie</h2>
    <form method="POST" action="monnaies.php">
        <p><label>Nom : <input type="text" name="nom" placeholder="Ex : Livre sterling"></label></p>
        <p><label>Symbole : <input type="text" name="symbole" placeholder="Ex : GBP"></label></p>
        <p><label>Taux (1 <?= MONNAIE_REFERENCE ?> = ? dans cette monnaie) :
                <input type="text" name="taux" value="1" placeholder="Ex : 0.25"></label></p>
        <p><label>Décimales :
                <select name="decimales">
                    <option value="0">0 (ex. : Ariary)</option>
                    <option value="2" selected>2 (ex. : Euro, Dollar)</option>
                    <option value="3">3 (ex. : Dinar)</option>
                </select></label></p>
        <p><button type="submit">Ajouter</button></p>
    </form>

    <h2>Liste des monnaies</h2>
    <table border="1" cellpadding="6">
        <tr>
            <th>Nom</th>
            <th>Symbole</th>
            <th>Taux (1 <?= MONNAIE_REFERENCE ?> =)</th>
            <th>Décimales</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($liste as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['nom']) ?></td>
                <td><?= htmlspecialchars($m['symbole']) ?></td>
                <td><?= htmlspecialchars($m['taux']) ?></td>
                <td><?= htmlspecialchars($m['decimales']) ?></td>
                <td>
                    <a href="supprimer_monnaie.php?id=<?= $m['id'] ?>"
                        onclick="return confirm('Supprimer cette monnaie ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>