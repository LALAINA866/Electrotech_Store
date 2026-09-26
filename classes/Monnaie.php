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
    // $taux : valeur de 1 unité de la monnaie de référence dans cette monnaie
    // $decimales : nombre de chiffres après la virgule (2 pour l'euro, 3 pour le dinar)
    public function ajouter($nom, $symbole, $taux = 1, $decimales = 2)
    {
        $sql = "INSERT INTO monnaies (nom, symbole, taux, decimales)
                VALUES (:nom, :sym, :taux, :dec)";
        $req = $this->pdo->prepare($sql);
        return $req->execute([
            ':nom'  => $nom,
            ':sym'  => $symbole,
            ':taux' => $taux,
            ':dec'  => $decimales
        ]);
    }

    // Trouver une monnaie par son symbole (sert à retrouver la monnaie de référence)
    public function trouverParSymbole($symbole)
    {
        $sql = "SELECT * FROM monnaies WHERE symbole = :sym";
        $req = $this->pdo->prepare($sql);
        $req->execute([':sym' => $symbole]);
        return $req->fetch();
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