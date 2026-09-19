<?php
class Produit
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // READ : lister les produits AVEC le nom de leur catégorie et fournisseur
    public function lister()
    {
        $sql = "SELECT p.*, c.nom AS categorie_nom, f.nom AS fournisseur_nom
                FROM produits p
                LEFT JOIN categories c ON p.categorie_id = c.id
                LEFT JOIN fournisseurs f ON p.fournisseur_id = f.id
                ORDER BY p.nom";
        $requete = $this->pdo->query($sql);
        return $requete->fetchAll();
    }

    // recherche des produits par son nom et filtrage par catégorie
    public function rechercher($nom, $categorie_id)
    {
        $sql = "SELECT p.*, c.nom AS categorie_nom, f.nom AS fournisseur_nom
                FROM produits p
                LEFT JOIN categories c ON p.categorie_id = c.id
                LEFT JOIN fournisseurs f ON p.fournisseur_id = f.id
                WHERE p.nom LIKE :nom
                AND (:categorie_id = '' OR p.categorie_id = :categorie_id)
                ORDER BY p.nom";
        $requete = $this->pdo->prepare($sql);
        $requete->execute([':nom' => '%'. $nom . '%', ':categorie_id' => $categorie_id]);
        return $requete->fetchAll();
            
    }

    // READ : un produit par son id
    public function trouver($id)
    {
        $sql = "SELECT * FROM produits WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        $requete->execute([':id' => $id]);
        return $requete->fetch();
    }

    // CREATE
    public function ajouter($nom, $description, $categorie_id, $fournisseur_id,
                            $prix_detail, $prix_gros, $quantite_stock, $seuil_alerte, $image)
    {
        $sql = "INSERT INTO produits
                (nom, description, categorie_id, fournisseur_id, prix_detail, prix_gros,
                 quantite_stock, seuil_alerte, image)
                VALUES
                (:nom, :description, :categorie_id, :fournisseur_id, :prix_detail, :prix_gros,
                 :quantite_stock, :seuil_alerte, :image)";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([
            ':nom' => $nom, ':description' => $description,
            ':categorie_id' => $categorie_id, ':fournisseur_id' => $fournisseur_id,
            ':prix_detail' => $prix_detail, ':prix_gros' => $prix_gros,
            ':quantite_stock' => $quantite_stock, ':seuil_alerte' => $seuil_alerte,
            ':image' => $image
        ]);
    }

    // UPDATE
    public function modifier($id, $nom, $description, $categorie_id, $fournisseur_id,
                             $prix_detail, $prix_gros, $quantite_stock, $seuil_alerte, $image)
    {
        $sql = "UPDATE produits SET
                nom = :nom, description = :description,
                categorie_id = :categorie_id, fournisseur_id = :fournisseur_id,
                prix_detail = :prix_detail, prix_gros = :prix_gros,
                quantite_stock = :quantite_stock, seuil_alerte = :seuil_alerte,
                image = :image
                WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([
            ':nom' => $nom, ':description' => $description,
            ':categorie_id' => $categorie_id, ':fournisseur_id' => $fournisseur_id,
            ':prix_detail' => $prix_detail, ':prix_gros' => $prix_gros,
            ':quantite_stock' => $quantite_stock, ':seuil_alerte' => $seuil_alerte,
            ':image' => $image, ':id' => $id
        ]);
    }

    // DELETE
    public function supprimer($id)
    {
        $sql = "DELETE FROM produits WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([':id' => $id]);
    }
}
?>