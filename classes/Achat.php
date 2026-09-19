<?php
class Achat
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Lister tous les achats avec le nom du fournisseur et de l'admin
    public function lister()
    {
        $sql = "SELECT a.*, f.nom AS fournisseur_nom, u.nom AS user_nom
                FROM achats a
                LEFT JOIN fournisseurs f ON a.fournisseur_id = f.id
                LEFT JOIN users u ON a.user_id = u.id
                ORDER BY a.date_achat DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

        // Enregistre un achat complet (en-tête + lignes) ET met à jour le stock
    public function enregistrer($fournisseur_id, $user_id, $produits, $quantites, $prix)
    {
        try {
            $this->pdo->beginTransaction();  // on ouvre la transaction

            // 1. Créer l'en-tête de l'achat (montant à 0 pour l'instant)
            $sql = "INSERT INTO achats (fournisseur_id, user_id, montant_total)
                    VALUES (:f, :u, 0)";
            $req = $this->pdo->prepare($sql);
            $req->execute([':f' => $fournisseur_id, ':u' => $user_id]);
            $achat_id = $this->pdo->lastInsertId();  // l'id de l'achat créé

            $montantTotal = 0;

            // 2. Parcourir chaque ligne de produit
            for ($i = 0; $i < count($produits); $i++) {
                $produit_id    = $produits[$i];
                $quantite      = (int) $quantites[$i];
                $prix_unitaire = (float) $prix[$i];

                // On ignore les lignes vides ou invalides
                if ($produit_id === "" || $quantite <= 0) {
                    continue;
                }

                // Insérer la ligne de détail
                $sql = "INSERT INTO achat_details (achat_id, produit_id, quantite, prix_unitaire)
                        VALUES (:a, :p, :q, :pu)";
                $req = $this->pdo->prepare($sql);
                $req->execute([':a' => $achat_id, ':p' => $produit_id,
                               ':q' => $quantite, ':pu' => $prix_unitaire]);

                // AUTOMATISATION : augmenter le stock du produit
                $sql = "UPDATE produits SET quantite_stock = quantite_stock + :q WHERE id = :p";
                $req = $this->pdo->prepare($sql);
                $req->execute([':q' => $quantite, ':p' => $produit_id]);

                $montantTotal += $quantite * $prix_unitaire;
            }

            // 3. Mettre à jour le montant total réel
            $sql = "UPDATE achats SET montant_total = :m WHERE id = :a";
            $req = $this->pdo->prepare($sql);
            $req->execute([':m' => $montantTotal, ':a' => $achat_id]);

            $this->pdo->commit();   // tout a réussi → on valide
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack(); // une erreur → on annule TOUT
            return false;
        }
    }

    
    //Méthodes pour voir les détails
    // Un achat par son id
    public function trouver($id)
    {
        $sql = "SELECT a.*, f.nom AS fournisseur_nom
                FROM achats a
                LEFT JOIN fournisseurs f ON a.fournisseur_id = f.id
                WHERE a.id = :id";
        $req = $this->pdo->prepare($sql);
        $req->execute([':id' => $id]);
        return $req->fetch();
    }

    // Les lignes de détail d'un achat
    public function details($achat_id)
    {
        $sql = "SELECT ad.*, p.nom AS produit_nom
                FROM achat_details ad
                LEFT JOIN produits p ON ad.produit_id = p.id
                WHERE ad.achat_id = :a";
        $req = $this->pdo->prepare($sql);
        $req->execute([':a' => $achat_id]);
        return $req->fetchAll();
    }
}
?>