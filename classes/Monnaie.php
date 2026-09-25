<?php
class Monnaie
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Lister toutes les monnaies
    public function lister()
    {
        $sql = "SELECT * FROM monnaies ORDER BY nom";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Trouver une monnaie par son id
    public function trouver($id)
    {
        $sql = "SELECT * FROM monnaies WHERE id = :id";
        $req = $this->pdo->prepare($sql);
        $req->execute([':id' => $id]);
        return $req->fetch();
    }

    // Ajouter une nouvelle monnaie
    public function ajouter($nom, $symbole)
    {
        $sql = "INSERT INTO monnaies (nom, symbole) VALUES (:nom, :sym)";
        $req = $this->pdo->prepare($sql);
        return $req->execute([':nom' => $nom, ':sym' => $symbole]);
    }

    // Supprimer une monnaie
    public function supprimer($id)
    {
        $sql = "DELETE FROM monnaies WHERE id = :id";
        $req = $this->pdo->prepare($sql);
        return $req->execute([':id' => $id]);
    }
}
?>