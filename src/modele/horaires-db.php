<?php

require_once "connexion-db.php";

function selectAllTimeAreas () : array {
    $connexion = createConnection();
    $requeteSQL = "SELECT * FROM horaires";
    $requete = $connexion->prepare($requeteSQL);
    $requete->execute();
    $horaires = $requete->fetchAll(PDO::FETCH_ASSOC);
    return $horaires;
}

function formaterHeure (array $horaire) : string {
    if (isset($horaire["heure_debut_matin"])) {
        $horaireDebutMatin = substr($horaire["heure_debut_matin"],0,2).":".substr($horaire["heure_debut_matin"],3,2);
        $horaireFinMatin = substr($horaire["heure_fin_matin"],0,2).":".substr($horaire["heure_fin_matin"],3,2);
        $horaireMatin = $horaireDebutMatin." - ".$horaireFinMatin;
        if (isset($horaire["heure_debut_apres_midi"])) {
            $horaireDebutApresMidi = substr($horaire["heure_debut_apres_midi"],0,2).":".substr($horaire["heure_debut_apres_midi"],3,2);
            $horaireFinApresMidi = substr($horaire["heure_fin_apres_midi"],0,2).":".substr($horaire["heure_fin_apres_midi"],3,2);
            $horaireApresMidi = $horaireDebutApresMidi." - ".$horaireFinApresMidi;
        }
        if (isset($horaireApresMidi)) {
            return $horaireMatin." | ".$horaireApresMidi;
        } else {
            return $horaireMatin;
        }
    } else {
        return "Fermé";
    }
}