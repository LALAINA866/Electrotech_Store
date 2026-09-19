<?php
class User
{
    private $pdo;

    // Le constructeur reçoit la connexion PDO pour pouvoir parler à la base
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Vérifie si un email est déjà utilisé (pour éviter les doublons)
    public function emailExiste($email)
    {
        $sql = "SELECT id FROM users WHERE email = :email";
        $requete = $this->pdo->prepare($sql);
        $requete->execute([':email' => $email]);
        return $requete->fetch() !== false; // true si trouvé, false sinon
    }

    // Inscrit un nouvel utilisateur
    public function inscrire($nom, $email, $motDePasse, $role = 'client')
    {
        // On chiffre (hache) le mot de passe : JAMAIS en clair dans la base
        $motDePasseHache = password_hash($motDePasse, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (nom, email, mot_de_passe, role)
                VALUES (:nom, :email, :mdp, :role)";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([
            ':nom'   => $nom,
            ':email' => $email,
            ':mdp'   => $motDePasseHache,
            ':role'  => $role
        ]);
    }

    // Vérifie l'email + le mot de passe. Retourne l'utilisateur si OK, sinon false.
    public function connecter($email, $motDePasse)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $requete = $this->pdo->prepare($sql);
        $requete->execute([':email' => $email]);
        $utilisateur = $requete->fetch();

        // On vérifie que l'utilisateur existe ET que le mot de passe correspond
        if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            return $utilisateur; // connexion réussie
        }
        return false; // email inconnu ou mauvais mot de passe
    }
}
?>