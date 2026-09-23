<?php
class Commande
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Enregistre une vente complète (en-tête + lignes) ET diminue le stock
    public function enregistrer($client_id, $type_vente, $produits, $quantites, $prix)
    {
        try {
            $this->pdo->beginTransaction();

            $nbLignesValides = 0;

            // 1. Vérifier d'abord que le stock est suffisant pour chaque produit
            for ($i = 0; $i < count($produits); $i++) {
                $produit_id = $produits[$i];
                $quantite   = (int) $quantites[$i];

                if ($produit_id === "" || $quantite <= 0) {
                    continue;
                }

                $nbLignesValides++;

                // On lit le stock actuel du produit
                $sql = "SELECT quantite_stock FROM produits WHERE id = :p";
                $req = $this->pdo->prepare($sql);
                $req->execute([':p' => $produit_id]);
                $stockActuel = $req->fetchColumn();

                // Si le stock est insuffisant → on annule TOUT
                if ($stockActuel < $quantite) {
                    $this->pdo->rollBack();
                    return "stock_insuffisant"; // message spécial
                }
            }

            // refuser si aucun produit valide
            if ($nbLignesValides === 0) {
            $this->pdo->rollBack();
            return "aucun_produit";
            }
            
            // 2. Créer l'en-tête de la commande (montant à 0 pour l'instant)
            $sql = "INSERT INTO commandes (client_id, type_vente, montant_total)
                    VALUES (:c, :t, 0)";
            $req = $this->pdo->prepare($sql);
            $req->execute([':c' => $client_id, ':t' => $type_vente]);
            $commande_id = $this->pdo->lastInsertId();

            $montantTotal = 0;

            // 3. Parcourir chaque ligne de produit
            for ($i = 0; $i < count($produits); $i++) {
                $produit_id    = $produits[$i];
                $quantite      = (int) $quantites[$i];
                $prix_unitaire = (float) $prix[$i];

                if ($produit_id === "" || $quantite <= 0) {
                    continue;
                }

                // Insérer la ligne de détail
                $sql = "INSERT INTO commande_details (commande_id, produit_id, quantite, prix_unitaire)
                        VALUES (:cmd, :p, :q, :pu)";
                $req = $this->pdo->prepare($sql);
                $req->execute([':cmd' => $commande_id, ':p' => $produit_id,
                               ':q' => $quantite, ':pu' => $prix_unitaire]);

                // AUTOMATISATION : DIMINUER le stock 
                $sql = "UPDATE produits SET quantite_stock = quantite_stock - :q WHERE id = :p";
                $req = $this->pdo->prepare($sql);
                $req->execute([':q' => $quantite, ':p' => $produit_id]);

                $montantTotal += $quantite * $prix_unitaire;
            }

            // 4. Mettre à jour le montant total réel
            $sql = "UPDATE commandes SET montant_total = :m WHERE id = :cmd";
            $req = $this->pdo->prepare($sql);
            $req->execute([':m' => $montantTotal, ':cmd' => $commande_id]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // Lister toutes les commandes (avec le nom du client)
    public function lister()
    {
        $sql = "SELECT c.*, u.nom AS client_nom
                FROM commandes c
                LEFT JOIN users u ON c.client_id = u.id
                ORDER BY c.date_commande DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Trouver une commande par son id
    public function trouver($id)
    {
        $sql = "SELECT c.*, u.nom AS client_nom
                FROM commandes c
                LEFT JOIN users u ON c.client_id = u.id
                WHERE c.id = :id";
        $req = $this->pdo->prepare($sql);
        $req->execute([':id' => $id]);
        return $req->fetch();
    }

    // Les lignes de détail d'une commande
    public function details($commande_id)
    {
        $sql = "SELECT cd.*, p.nom AS produit_nom
                FROM commande_details cd
                LEFT JOIN produits p ON cd.produit_id = p.id
                WHERE cd.commande_id = :cmd";
        $req = $this->pdo->prepare($sql);
        $req->execute([':cmd' => $commande_id]);
        return $req->fetchAll();
    }
}
?>