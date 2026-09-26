<?php
/**
 * Informations de la boutique (émetteur des factures).
 * Projet : ElectroTech Store
 */

// Taux de TVA appliqué sur les factures (20 %)
const TAUX_TVA = 0.20;

// Symbole de la monnaie de référence : celle dans laquelle les prix sont enregistrés en base
// (son taux dans la table monnaies doit être égal à 1)
const MONNAIE_REFERENCE = 'DT';

$boutique = [
    'nom'        => 'ElectroTech Store',
    'slogan'     => 'Votre univers tech, à portée de clic',
    'activite'   => 'Matériel électronique, informatique & électroménager',
    'adresse'    => '25, Avenue de la Liberté',
    'ville'      => '1002 Tunis, Tunisie',
    'telephone'  => '+216 71 000 000',
    'email'      => 'contact@electrotech-store.com',
    'site'       => 'www.electrotech-store.com',
    'matricule'  => '0000000A/A/M/000',   // Matricule fiscal (à remplacer)
    'rc'         => 'B000000000000',      // Registre de commerce (à remplacer)
    'logo'       => __DIR__ . '/../assets/img/logo_electrotech.png',

    // Conditions et coordonnées affichées en bas de facture
    'conditions' => 'Paiement à réception de la facture.',
    'moyens'     => 'Virement bancaire, espèces ou carte bancaire',
    'banque'     => 'Banque : (à compléter)',
    'rib'        => 'RIB : (à compléter)',
];
?>