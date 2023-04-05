<?php
    session_start();
    if (isset($_GET["deco"])) {
        unset($_SESSION["user"]);
        header("Location: detail-etudiant.php");
    } else {
        require_once "src/modele/etudiant-db.php";
        require_once "src/modele/promotion-db.php";
        require_once "src/outils/dates.php";

        if (!empty($_GET['id'])) {
            $id = intval($_GET['id']);
        }

        $etudiant = selectStudentById($id);
        $promotion = selectPromotionById($etudiant["id_promotion"]);
        $_SESSION["last-visited"] = "detail-etudiant.php?id=$id";
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
    <title>Détails de l'étudiant</title>
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
                    <li><a href="detail-etudiant.php?id=<?= $id?>">Déconnexion</a></li>
                <?php } else { ?>
                    <li><a href="connexion.php">Connexion</a></li>
                <?php } ?>
            </ul>
        </header>

        <div class="content2">

            <div class="carte2">
                <img src="images/<?= $etudiant["nom_fichier_photo"]?>" alt="Photo étudiant" class="image2">
                <div class="nom-prenom2">
                    <div><?= ucfirst(strtolower($etudiant["prenom_etudiant"]))." ".mb_strtoupper($etudiant["nom_etudiant"]) ?></div>
                </div>

                <div class="promotion">
                    <div><i class="fa-solid fa-graduation-cap"></i> : <?= $promotion["intitule_promotion"] ?></div>
                </div>

                <div class="date-naissance2">
                    <div><i class="fa-solid fa-cake-candles"></i> : <?= formaterDate($etudiant["date_naissance_etudiant"]) ?></div>
                </div>

                <div class="age2">
                    <?php if (calculerAge($etudiant["date_naissance_etudiant"]) >= 18) { ?>
                        <div class="vert"><?= calculerAge($etudiant["date_naissance_etudiant"])." ans" ?></div>
                    <?php } else { ?>
                        <div class="rouge"><?= calculerAge($etudiant["date_naissance_etudiant"])." ans" ?></div>
                    <?php } ?>
                </div>

                <div class="adresse">
                    <div><i class="fa-solid fa-house"></i> : <?= $etudiant["addresse_etudiant"] ?></div>
                </div>

                <div class="email">
                    <div><i class="fa-solid fa-envelope"></i> : <?= $etudiant["email_etudiant"] ?></div>
                </div>

                <div class="telephone">
                    <div><i class="fa-solid fa-phone"></i> : <?php if (strlen(strval($etudiant["telephone_etudiant"])) == 9) {
                            $numeroTelephone = "0".$etudiant["telephone_etudiant"];
                            $numeroFormate = str_replace("\r\n", " ", chunk_split($numeroTelephone, 2));
                            echo $numeroFormate;
                        } else {
                            $numeroTelephone = strval($etudiant["telephone_etudiant"]);
                            $numeroFormate = str_replace("\r\n", " ", chunk_split($numeroTelephone, 2));
                            echo $numeroFormate;
                        } ?></div>
                </div>

                <a href="index.php" class="bouton2">Retour</a>
            </div>

        </div>

        <div class="footer">
            <div>Contacts :</div>
            <div class="contact">
                <a href="https://twitter.com/"><i class="fa-brands fa-twitter"></i></a>
                <a href="https://instagram.com/"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://facebook.com/"><i class="fa-brands fa-facebook"></i></a>
            </div>
        </div>

    </div>

</body>
</html>