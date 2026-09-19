<?php
class Categorie
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // READ : récupérer toutes les catégories
    public function lister()
    {
        $sql = "SELECT * FROM categories ORDER BY nom";
        $requete = $this->pdo->query($sql);
        return $requete->fetchAll();
    }

    // READ : récupérer UNE catégorie par son id (utile pour la modification)
    public function trouver($id)
    {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        $requete->execute([':id' => $id]);
        return $requete->fetch();
    }

    // CREATE : ajouter une catégorie
    public function ajouter($nom, $description)
    {
        $sql = "INSERT INTO categories (nom, description) VALUES (:nom, :description)";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([':nom' => $nom, ':description' => $description]);
    }

    // UPDATE : modifier une catégorie existante
    public function modifier($id, $nom, $description)
    {
        $sql = "UPDATE categories SET nom = :nom, description = :description WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([':nom' => $nom, ':description' => $description, ':id' => $id]);
    }

    // DELETE : supprimer une catégorie
    public function supprimer($id)
    {
        $sql = "DELETE FROM categories WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([':id' => $id]);
    }
}
?>