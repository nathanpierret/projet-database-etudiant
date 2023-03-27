<?php

require_once "../src/modele/etudiant-db.php";

$etudiant = selectAllPromotions();

$promotion = selectPromotionById(1);

$etudiants = selectStudentsByPromotion(1);

print_r($etudiant);
echo PHP_EOL;
echo PHP_EOL;
print_r($promotion);
print_r($etudiants);

//Affichage du résultat de la requête

/*if (!$etudiant) {
   echo "Aucun étudiant n'a cet id !";
} else {
    echo $etudiant["id_etudiant"]." ".
        $etudiant["prenom_etudiant"]." ".
        $etudiant["nom_etudiant"]." ".
        $etudiant["email_etudiant"].
        PHP_EOL;
}*/