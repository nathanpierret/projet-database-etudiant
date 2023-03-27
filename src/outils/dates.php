<?php

function formaterDate (string $date) : string {
    $chaine = "";
    $tableau = explode("-",$date);
    for ($i=2;$i>=0;$i--) {
        $chaine = $chaine.$tableau[$i]."/";
    }
    return substr($chaine,0,strlen($chaine)-1);
}

function calculerAge (string $date) : int {
    $tableau = explode("-",$date);
    $annee = $tableau[0];
    $mois = $tableau[1];
    $jour = $tableau[2];
    $age = intval(date("Y"))-intval($annee);
    if (intval(date("n")) < intval($mois)) {
        $age = $age - 1;
    }
    if (intval(date("n")) == intval($mois)) {
        if (intval(date("j")) < intval($jour)) {
            $age = $age - 1;
        }
    }
    return $age;
}