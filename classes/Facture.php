<?php
class Facture
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Vérifie si une facture existe déjà pour cette commande
    public function trouverParCommande($commande_id)
    {
        $sql = "SELECT * FROM factures WHERE commande_id = :cmd";
        $req = $this->pdo->prepare($sql);
        $req->execute([':cmd' => $commande_id]);
        return $req->fetch();
    }

    // Crée une facture pour une commande donnée
    public function creer($commande_id, $montant_total)
    {
        // Génère un numéro de facture unique basé sur la date + l'id de commande
        $numero = 'FACT-' . date('Ymd') . '-' . $commande_id;

        $sql = "INSERT INTO factures (commande_id, numero_facture, montant_total)
                VALUES (:cmd, :num, :m)";
        $req = $this->pdo->prepare($sql);
        $req->execute([':cmd' => $commande_id, ':num' => $numero, ':m' => $montant_total]);
        return $numero;
    }

    // Récupère une facture par son id
    public function trouver($id)
    {
        $sql = "SELECT * FROM factures WHERE id = :id";
        $req = $this->pdo->prepare($sql);
        $req->execute([':id' => $id]);
        return $req->fetch();
    }


    /**
     * Calcule les montants de la facture dans la monnaie choisie.
     * - Les prix en base sont HT, dans la monnaie de référence.
     * - On les convertit avec le taux de la monnaie choisie.
     * - On applique la TVA ($tauxTva, ex. : 0.20 pour 20 %).
     */
    public function calculer($lignes, $taux, $decimales, $tauxTva)
    {
        $resultat = [];
        $totalHt  = 0;

        foreach ($lignes as $l) {
            $prixHt  = round($l['prix_unitaire'] * $taux, $decimales);   // prix unitaire converti
            $ligneHt = round($prixHt * $l['quantite'], $decimales);
            $tva     = round($ligneHt * $tauxTva, $decimales);

            $resultat[] = [
                'nom'         => $l['produit_nom'],
                'description' => $l['produit_description'] ?? '',
                'image'       => $l['produit_image'] ?? null,
                'quantite'    => $l['quantite'],
                'prix_ht'     => $prixHt,
                'total_ht'    => $ligneHt,
                'tva'         => $tva,
                'total_ttc'   => $ligneHt + $tva,
            ];
            $totalHt += $ligneHt;
        }

        $totalTva = round($totalHt * $tauxTva, $decimales);

        return [
            'lignes' => $resultat,
            'totaux' => [
                'ht'  => $totalHt,
                'tva' => $totalTva,
                'ttc' => $totalHt + $totalTva,
            ],
        ];
    }
}
