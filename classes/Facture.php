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
}
?>