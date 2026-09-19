<?php
class Fournisseur
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // READ : toutes les fournisseurs
    public function lister()
    {
        $sql = "SELECT * FROM fournisseurs ORDER BY nom";
        $requete = $this->pdo->query($sql);
        return $requete->fetchAll();
    }

    // READ : un fournisseur par son id
    public function trouver($id)
    {
        $sql = "SELECT * FROM fournisseurs WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        $requete->execute([':id' => $id]);
        return $requete->fetch();
    }

    // CREATE
    public function ajouter($nom, $email, $telephone, $adresse)
    {
        $sql = "INSERT INTO fournisseurs (nom, email, telephone, adresse)
                VALUES (:nom, :email, :telephone, :adresse)";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([
            ':nom' => $nom, ':email' => $email,
            ':telephone' => $telephone, ':adresse' => $adresse
        ]);
    }

    // UPDATE
    public function modifier($id, $nom, $email, $telephone, $adresse)
    {
        $sql = "UPDATE fournisseurs
                SET nom = :nom, email = :email, telephone = :telephone, adresse = :adresse
                WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([
            ':nom' => $nom, ':email' => $email,
            ':telephone' => $telephone, ':adresse' => $adresse, ':id' => $id
        ]);
    }

    // DELETE
    public function supprimer($id)
    {
        $sql = "DELETE FROM fournisseurs WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([':id' => $id]);
    }
}
?>