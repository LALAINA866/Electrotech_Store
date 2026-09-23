<?php
require_once '../includes/auth.php';
exigerAdmin();

require_once '../config/database.php';
require_once '../classes/Commande.php';
require_once '../classes/Facture.php';
require_once '../libs/fpdf/fpdf.php';   // ← on charge la librairie FPDF

$commande = new Commande($pdo);
$facture  = new Facture($pdo);

// On récupère la commande concernée
$id = $_GET['id'] ?? null;
if (!$id) { header('Location: commandes.php'); exit; }

$entete = $commande->trouver($id);
if (!$entete) { header('Location: commandes.php'); exit; }

$lignes = $commande->details($id);

// Créer la facture en base si elle n'existe pas encore
$factureExistante = $facture->trouverParCommande($id);
if (!$factureExistante) {
    $numero = $facture->creer($id, $entete['montant_total']);
} else {
    $numero = $factureExistante['numero_facture'];
}

// ---- Génération du PDF avec FPDF ----
$pdf = new FPDF();
$pdf->AddPage();

// Titre
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 10, 'FACTURE', 0, 1, 'C');
$pdf->Ln(5);

// Infos de la facture
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, 'Numero : ' . $numero, 0, 1);
$pdf->Cell(0, 7, 'Date : ' . date('d/m/Y'), 0, 1);
$pdf->Cell(0, 7, 'Client : ' . $entete['client_nom'], 0, 1);
$pdf->Cell(0, 7, 'Type de vente : ' . $entete['type_vente'], 0, 1);
$pdf->Ln(5);

// En-tête du tableau des produits
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(80, 8, 'Produit', 1);
$pdf->Cell(25, 8, 'Quantite', 1, 0, 'C');
$pdf->Cell(35, 8, 'Prix unitaire', 1, 0, 'C');
$pdf->Cell(40, 8, 'Sous-total', 1, 1, 'C');

// Lignes des produits
$pdf->SetFont('Arial', '', 11);
foreach ($lignes as $l) {
    $sousTotal = $l['quantite'] * $l['prix_unitaire'];
    $pdf->Cell(80, 8, $l['produit_nom'], 1);
    $pdf->Cell(25, 8, $l['quantite'], 1, 0, 'C');
    $pdf->Cell(35, 8, number_format($l['prix_unitaire'], 2), 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($sousTotal, 2), 1, 1, 'R');
}

// Montant total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(140, 8, 'MONTANT TOTAL', 1);
$pdf->Cell(40, 8, number_format($entete['montant_total'], 2), 1, 1, 'R');

// Afficher le PDF dans le navigateur
$pdf->Output('I', 'Facture_' . $numero . '.pdf');
?>