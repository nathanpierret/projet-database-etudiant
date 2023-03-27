<?php
require_once "connexion-db.php";

function addUser (string $nom, string $prenom, string $email, string $motDePasse) {
    $connexion = createConnection();
    $requeteSQL = "INSERT INTO utilisateurs (prenom_utilisateur, nom_utilisateur, email_utilisateur, mot_de_passe) 
                    VALUES (:prenom, :nom, :email, :motDePasse)";
    $requete = $connexion->prepare($requeteSQL);
    $requete->bindValue(":prenom",$prenom);
    $requete->bindValue(":nom",$nom);
    $requete->bindValue(":email",$email);
    $requete->bindValue(":motDePasse",$motDePasse);
    $requete->execute();
}

function selectAllUsers () : array {
    $connexion = createConnection();
    $requeteSQL = "SELECT * FROM utilisateurs";
    $requete = $connexion->prepare($requeteSQL);
    $requete->execute();
    $users = $requete->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

function selectUserByEmail (string $email) : array {
    $connexion = createConnection();
    $requeteSQL = "SELECT * FROM utilisateurs WHERE email_utilisateur = :email";
    $requete = $connexion->prepare($requeteSQL);
    $requete->bindValue(":email",$email);
    $requete->execute();
    $user = $requete->fetch(PDO::FETCH_ASSOC);
    return $user;
}

function verifyPassword (string | null $motDePasse) : string | null {
    if ($motDePasse == null) {
        return null;
    } elseif (!preg_match('@[A-Z]@',$motDePasse) || !preg_match('@[a-z]@',$motDePasse) || !preg_match('@\d@',$motDePasse) ||
    !preg_match('@\W@',$motDePasse)) {
        return "Le mot de passe doit au moins contenir une majuscule, une minuscule, un chiffre et un caractère spécial !";
    } elseif (strlen($motDePasse) < 8 || strlen($motDePasse) > 15) {
        return "Le mot de passe doit contenir entre 8 et 15 caractères !";
    } else {
        return null;
    }
}