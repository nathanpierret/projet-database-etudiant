<?php

require_once "connexion-db.php";

function selectAllStudents () : array {
    $connexion = createConnection();
    $requeteSQL = "SELECT * FROM etudiant";
    $requete = $connexion->prepare($requeteSQL);
    $requete->execute();
    $etudiants = $requete->fetchAll(PDO::FETCH_ASSOC);
    return $etudiants;
}

function selectStudentById (int $id) : array {
    $connexion = createConnection();
    $requeteSQL = "SELECT * FROM etudiant WHERE id_etudiant = :id";
    $requete = $connexion->prepare($requeteSQL);
    $requete->bindValue(":id",$id);
    $requete->execute();
    $etudiant = $requete->fetch(PDO::FETCH_ASSOC);
    return $etudiant;
}

function addStudent (string $nom, string $prenom, string $email, string $adresse, string $telephone, string $dateNaissance, string $nomFichier, string | null $idPromotion) {
    $connexion = createConnection();
    $requeteSQL = "INSERT INTO etudiant (prenom_etudiant, nom_etudiant, email_etudiant, date_naissance_etudiant, addresse_etudiant, telephone_etudiant, nom_fichier_photo, id_promotion) 
                    VALUES (:prenom, :nom, :email, :dateNaissance, :adresse, :telephone, :nomFichier, :idPromotion)";
    $requete = $connexion->prepare($requeteSQL);
    $requete->bindValue(":prenom",$prenom);
    $requete->bindValue(":nom",$nom);
    $requete->bindValue(":email",$email);
    $requete->bindValue(":dateNaissance",$dateNaissance);
    $requete->bindValue(":adresse",$adresse);
    $requete->bindValue(":telephone",$telephone);
    $requete->bindValue(":nomFichier",$nomFichier);
    $requete->bindValue(":idPromotion",$idPromotion);
    $requete->execute();
}

function selectStudentsByPromotion (int | null $id_promotion) : array {
    $connexion = createConnection();
    if ($id_promotion == null) {
        $requeteSQL = "SELECT * FROM etudiant WHERE id_promotion is null";
        $requete = $connexion->prepare($requeteSQL);
    } else {
        $requeteSQL = "SELECT * FROM etudiant WHERE id_promotion = :id";
        $requete = $connexion->prepare($requeteSQL);
        $requete->bindValue(":id",$id_promotion);
    }
    $requete->execute();
    $etudiants = $requete->fetchAll(PDO::FETCH_ASSOC);
    return $etudiants;
}