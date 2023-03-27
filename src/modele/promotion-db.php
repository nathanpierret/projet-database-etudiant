<?php

require_once "connexion-db.php";

function selectAllPromotions () : array {
    $connexion = createConnection();
    $requeteSQL = "SELECT * FROM promotion";
    $requete = $connexion->prepare($requeteSQL);
    $requete->execute();
    $promotions = $requete->fetchAll(PDO::FETCH_ASSOC);
    return $promotions;
}

function selectPromotionById (int | null $id) : array {
    if ($id == null) {
        return ["intitule_promotion" => "Aucune promotion"];
    } else {
        $connexion = createConnection();
        $requeteSQL = "SELECT intitule_promotion FROM promotion WHERE id_promotion = :id";
        $requete = $connexion->prepare($requeteSQL);
        $requete->bindValue(":id",$id);
        $requete->execute();
        $promotion = $requete->fetch(PDO::FETCH_ASSOC);
        return $promotion;
    }
}