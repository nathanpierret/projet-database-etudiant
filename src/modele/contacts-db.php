<?php

require_once "connexion-db.php";

function addMail (string $prenom, string $nom, string $mail, string $telephone, string $motif, string $message) {
    $traitement = "Pas traité";
    $timestmp = new DateTimeImmutable();
    $heureTraitement = $timestmp->getTimestamp();
    $connexion = createConnection();
    $requeteSQL = "INSERT INTO contacts (prenom_emetteur, nom_emetteur, mail_emetteur, telephone_emetteur, objet_mail, message_mail, traitement_mail, heure_envoi) 
                    VALUES (:prenom, :nom, :email, :telephone, :motif, :message, :traitement, :heure)";
    $requete = $connexion->prepare($requeteSQL);
    $requete->bindValue(":prenom",$prenom);
    $requete->bindValue(":nom",$nom);
    $requete->bindValue(":email",$mail);
    $requete->bindValue(":telephone",$telephone);
    $requete->bindValue(":motif",$motif);
    $requete->bindValue(":message",$message);
    $requete->bindValue(":traitement",$traitement);
    $requete->bindValue(":heure", str_replace("T"," ",substr(date("c",$heureTraitement),0,19)));
    $requete->execute();
}