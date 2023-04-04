<?php

require_once "src/modele/etudiant-db.php";
require_once "src/modele/promotion-db.php";
require_once "src/outils/dates.php";

$promotions = selectAllPromotions();
$etudiants = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filtrePromotion = $_POST["filtre-promotion"];
    $etudiants = selectStudentsByPromotion(intval($filtrePromotion));
    $promotion = selectPromotionById(intval($filtrePromotion));
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
          integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Best Students</title>
</head>
<body>

<div class="container">

    <header class="header">
        <img src="images/Logo_Best_Students2.png" alt="Logo Best Students">
        <ul>
            <li><a href="index.php">Liste des étudiants</a></li>
            <li><a href="ajout-etudiant.php">Ajouter un étudiant</a></li>
            <li><a href="liste-promotions.php">Liste des promotions</a></li>
            <li><a href="contacts.php">Contactez-nous</a></li>
            <?php if (isset($_SESSION["user"])) { ?>
                <li><a href="<?php unset($_SESSION["user"])?>">Déconnexion</a></li>
            <?php } else { ?>
                <li><a href="connexion.php">Connexion</a></li>
            <?php } ?>
        </ul>
    </header>

    <div class="content3">

        <form action="" method="post">
            <label for="filtre-promotion">Sélectionnez une promotion :</label>
            <div class="flex-form">
                <select name="filtre-promotion" id="filtre-promotion">
                    <option value="">Aucune</option>
                    <?php foreach ($promotions as $promotion) { ?>
                        <option value="<?= $promotion["id_promotion"] ?>"><?= $promotion["intitule_promotion"] ?></option>
                    <?php } ?>
                </select>
                <input type="submit" value="Sélectionner">
            </div>
        </form>

        <div>
        <?php
        if (isset($etudiants["0"])) {
            foreach ($etudiants as $etudiant) { ?>
                <div class="carte">
                    <img src="images/<?=$etudiant["nom_fichier_photo"]?>" alt="Photo étudiant" class="image">
                    <div class="nom-prenom">
                        <div><?= ucfirst(strtolower($etudiant["prenom_etudiant"]))." ".mb_strtoupper($etudiant["nom_etudiant"]) ?></div>
                    </div>
                    <div class="date-naissance">
                        <div><?= formaterDate($etudiant["date_naissance_etudiant"]) ?></div>
                    </div>
                    <div class="age">
                        <?php if (calculerAge($etudiant["date_naissance_etudiant"]) >= 18) { ?>
                            <div class="vert"><?= calculerAge($etudiant["date_naissance_etudiant"])." ans" ?></div>
                        <?php } else { ?>
                            <div class="rouge"><?= calculerAge($etudiant["date_naissance_etudiant"])." ans" ?></div>
                        <?php } ?>
                    </div>
                    <a href="detail-etudiant.php?id=<?=$etudiant['id_etudiant']?>" class="bouton">Détails</a>
                </div>
            <?php } ?>
        <?php } ?>
        </div>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' and !isset($etudiants["0"])) { ?>
            <div class="aucunEtudiant">Il n'y a aucun étudiant dans cette promotion.</div>
        <?php }?>

    </div>

    <footer class="footer">
        <div>Contacts :</div>
        <div class="contact">
            <a href="https://twitter.com/"><i class="fa-brands fa-twitter"></i></a>
            <a href="https://instagram.com/"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://facebook.com/"><i class="fa-brands fa-facebook"></i></a>
        </div>
    </footer>

</div>

</body>
</html>