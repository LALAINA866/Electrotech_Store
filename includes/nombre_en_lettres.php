<?php
/**
 * Conversion d'un nombre en toutes lettres (français).
 */

// Convertit un entier de 0 à 999 en lettres
function centainesEnLettres($n)
{
    $unites = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf',
               'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize',
               'dix-sept', 'dix-huit', 'dix-neuf'];
    $dizaines = ['', '', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante'];

    $centaine = intdiv($n, 100);
    $reste    = $n % 100;
    $texte    = '';

    // Partie des centaines
    if ($centaine > 0) {
        $texte = ($centaine === 1) ? 'cent' : $unites[$centaine] . ' cent';
        // « cents » prend un s seulement s'il termine le nombre (ex. : deux cents)
        if ($centaine > 1 && $reste === 0) {
            $texte .= 's';
        }
    }

    // Partie de 1 à 99
    if ($reste > 0) {
        if ($reste < 20) {
            $partie = $unites[$reste];
        } elseif ($reste < 70) {
            $d = intdiv($reste, 10);
            $u = $reste % 10;
            $partie = $dizaines[$d];
            if ($u === 1)   $partie .= ' et un';            // vingt et un, trente et un...
            elseif ($u > 1) $partie .= '-' . $unites[$u];
        } elseif ($reste < 80) {
            // 70 à 79 : soixante-dix, soixante et onze, soixante-douze...
            $partie = ($reste === 71) ? 'soixante et onze' : 'soixante-' . $unites[$reste - 60];
        } else {
            // 80 à 99 : quatre-vingts, quatre-vingt-un, quatre-vingt-dix...
            $partie = ($reste === 80) ? 'quatre-vingts' : 'quatre-vingt-' . $unites[$reste - 80];
        }
        $texte .= ($texte !== '' ? ' ' : '') . $partie;
    }

    return $texte;
}

// Convertit un entier positif (jusqu'aux milliards) en lettres
function entierEnLettres($n)
{
    $n = (int) $n;
    if ($n === 0) {
        return 'zéro';
    }

    $milliards = intdiv($n, 1000000000);
    $millions  = intdiv($n % 1000000000, 1000000);
    $milliers  = intdiv($n % 1000000, 1000);
    $reste     = $n % 1000;
    $morceaux  = [];

    if ($milliards > 0) {
        $morceaux[] = centainesEnLettres($milliards) . ' milliard' . ($milliards > 1 ? 's' : '');
    }
    if ($millions > 0) {
        $morceaux[] = centainesEnLettres($millions) . ' million' . ($millions > 1 ? 's' : '');
    }
    if ($milliers > 0) {
        // « mille » est invariable, et on dit « mille » et non « un mille »
        $mot = ($milliers === 1) ? 'mille' : centainesEnLettres($milliers) . ' mille';
        // devant « mille », « vingts » et « cents » perdent leur s (deux cent mille)
        $mot = preg_replace('/(vingt|cent)s mille$/', '$1 mille', $mot);
        $morceaux[] = $mot;
    }
    if ($reste > 0) {
        $morceaux[] = centainesEnLettres($reste);
    }

    return implode(' ', $morceaux);
}

// Met le nom de la monnaie au pluriel si nécessaire (euro -> euros ; ariary reste invariable)
function nomMonnaie($nom, $quantite)
{
    $nom = mb_strtolower($nom, 'UTF-8');
    if ($quantite > 1 && !preg_match('/[sxy]$/', $nom)) {
        $nom .= 's';
    }
    return $nom;
}

// Montant complet en lettres, ex. : « Mille deux cent cinquante euros et cinquante centimes »
function montantEnLettres($montant, $nomMonnaie, $decimales)
{
    $decimales = (int) $decimales;
    $montant   = round($montant, $decimales);

    $partieEntiere  = (int) floor($montant);
    $partieDecimale = (int) round(($montant - $partieEntiere) * pow(10, $decimales));

    $texte = entierEnLettres($partieEntiere);
    $nom   = nomMonnaie($nomMonnaie, $partieEntiere);

    // « un million d'euros », « deux millions de dinars »
    if (preg_match('/(million|milliard)s?$/', $texte)) {
        $texte .= preg_match('/^[aeiouy]/', $nom) ? " d'" . $nom : ' de ' . $nom;
    } else {
        $texte .= ' ' . $nom;
    }

    if ($decimales > 0 && $partieDecimale > 0) {
        // 3 décimales = millimes (dinar), 2 décimales = centimes (euro, dollar...)
        $sousUnite = ($decimales === 3) ? 'millime' : 'centime';
        $texte .= ' et ' . entierEnLettres($partieDecimale) . ' ' . $sousUnite . ($partieDecimale > 1 ? 's' : '');
    }

    // Majuscule sur la première lettre
    return mb_strtoupper(mb_substr($texte, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($texte, 1, null, 'UTF-8');
}
?>