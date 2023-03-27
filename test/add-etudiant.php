<?php

require_once "../src/modele/etudiant-db.php";

$etudiants = selectAllStudents();

if (empty($etudiants)) {
    echo "Aucun enregistrement !";
} else {
    foreach ($etudiants as $etudiant) {
        echo $etudiant["id_etudiant"]." ".
            $etudiant["prenom_etudiant"]." ".
            $etudiant["nom_etudiant"]." ".
            $etudiant["email_etudiant"]." ".
            PHP_EOL;
    }
}