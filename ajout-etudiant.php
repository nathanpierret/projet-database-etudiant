<?php

    require_once "src/modele/etudiant-db.php";
    require_once "src/modele/promotion-db.php";

    $promotions = selectAllPromotions();

    $prenom = null;
    $nom = null;
    $dateNaissance = null;
    $rue = null;
    $codePostal = null;
    $ville = null;
    $mail = null;
    $telephone = null;
    $promotion = null;
    $erreurs = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (empty(trim($_POST["prenom"]))) {
        $erreurs["prenom"] = "Le prénom est obligatoire !";
    } else {
        $prenom = trim($_POST["prenom"]);
    }

    if (empty(trim($_POST["nom"]))) {
        $erreurs["nom"] = "Le nom est obligatoire !";
    } else {
        $nom = trim($_POST["nom"]);
    }

    if (empty(trim($_POST["date-naissance"]))) {
        $erreurs["dateNaissance"] = "La date de naissance est obligatoire !";
    } else {
        $dateNaissance = trim($_POST["date-naissance"]);
    }

    if (empty(trim($_POST["rue"]))) {
        $erreurs["rue"] = "La rue est obligatoire !";
    } else {
        $rue = trim($_POST["rue"]);
    }

    if (empty(trim($_POST["code-postal"]))) {
        $erreurs["codePostal"] = "Le code postal est obligatoire !";
    } else {
        $codePostal = trim($_POST["code-postal"]);
    }

    if (empty(trim($_POST["ville"]))) {
        $erreurs["ville"] = "La ville est obligatoire !";
    } else {
        $ville = trim($_POST["ville"]);
    }

    if (empty(trim($_POST["mail"]))) {
        $erreurs["mail"] = "L'e-mail est obligatoire !";
    } elseif (!filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) {
        $erreurs["mail"] = "Il faut que l'adresse mail soit valide (avec un @ et un nom de domaine valide) !";
    } else {
        $mail = trim($_POST["mail"]);
    }

    if (empty(trim($_POST["telephone"]))) {
        $erreurs["telephone"] = "Le numéro de téléphone est obligatoire !";
    } else {
        $telephone = trim($_POST["telephone"]);
    }

    if (!empty($_POST["promotion"])) {
        $promotion = $_POST["promotion"];
    }

    if (empty($_FILES["photo"]["name"])) {
        $erreurs["photo"] = "La photo est obligatoire !";
    } else {
        $nomFichier = $_FILES["photo"]["name"];
        $typeFichier = $_FILES["photo"]["type"];
        $tmpFichier = $_FILES["photo"]["tmp_name"];
        $tailleFichier = $_FILES["photo"]["size"];
        $extensionFichier = pathinfo($nomFichier,PATHINFO_EXTENSION);
        if (!str_contains($typeFichier, "image")) {
            $erreurs["photo"] = "Seules les images sont acceptées !";
        } else {
            //Tester la taille du fichier
            if ($tailleFichier > 600 * 1024) {
                $erreurs["photo"] = "Une image ne doit pas dépasser 600 ko !";
            } else {
                $nomFichier = uniqid().".".$extensionFichier;
                if (!move_uploaded_file($tmpFichier,"images/$nomFichier")) {
                    $erreurs["photo"] = "Un problème interne est survenu !";
                }
            }
        }
    }
    if (empty($erreurs)) {
        $adresse = $rue." ".$codePostal." ".$ville;
        addStudent($nom, $prenom, $mail,$adresse, $telephone, $dateNaissance, $nomFichier,$promotion);
        header("Location: index.php");
    }
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
            <li><a href="connexion.php">Connexion</a></li>
        </ul>
    </header>

    <div class="content">

        <form action="" method="post" enctype="multipart/form-data">
            <label for="prenom">Prénom <span class="etoile">*</span></label>
            <input type="text" name="prenom" id="prenom" value="<?= $prenom ?>">

            <?php if (isset($erreurs["prenom"])) {?>
                <p class="erreur-validation"><?= $erreurs["prenom"] ?></p>
            <?php } ?>

            <label for="nom">Nom <span class="etoile">*</span></label>
            <input type="text" name="nom" id="nom" value="<?= $nom ?>">

            <?php if (isset($erreurs["nom"])) {?>
                <p class="erreur-validation"><?= $erreurs["nom"] ?></p>
            <?php } ?>

            <label for="date-naissance">Date de naissance <span class="etoile">*</span></label>
            <input type="date" name="date-naissance" id="date-naissance" value="<?= $dateNaissance ?>">

            <?php if (isset($erreurs["dateNaissance"])) {?>
                <p class="erreur-validation"><?= $erreurs["dateNaissance"] ?></p>
            <?php } ?>

            <label for="rue">Rue <span class="etoile">*</span></label>
            <input type="text" name="rue" id="rue" value="<?= $rue ?>">

            <?php if (isset($erreurs["rue"])) {?>
                <p class="erreur-validation"><?= $erreurs["rue"] ?></p>
            <?php } ?>

            <label for="code-postal">Code Postal <span class="etoile">*</span></label>
            <input type="text" name="code-postal" id="code-postal" value="<?= $codePostal ?>">

            <?php if (isset($erreurs["codePostal"])) {?>
                <p class="erreur-validation"><?= $erreurs["codePostal"] ?></p>
            <?php } ?>

            <label for="ville">Ville <span class="etoile">*</span></label>
            <input type="text" name="ville" id="ville" value="<?= $ville ?>">

            <?php if (isset($erreurs["ville"])) {?>
                <p class="erreur-validation"><?= $erreurs["ville"] ?></p>
            <?php } ?>

            <label for="mail">E-mail <span class="etoile">*</span></label>
            <input type="text" name="mail" id="mail" value="<?= $mail ?>">

            <?php if (isset($erreurs["mail"])) {?>
                <p class="erreur-validation"><?= $erreurs["mail"] ?></p>
            <?php } ?>

            <label for="telephone">Téléphone <span class="etoile">*</span></label>
            <input type="text" name="telephone" id="telephone" value="<?= $telephone ?>">

            <?php if (isset($erreurs["telephone"])) {?>
                <p class="erreur-validation"><?= $erreurs["telephone"] ?></p>
            <?php } ?>

            <label for="promotion">Promotion</label>
            <select name="promotion" id="promotion">
                <option value="">Aucune</option>
                <?php foreach ($promotions as $promotion) { ?>
                    <option value="<?= $promotion["id_promotion"] ?>"><?= $promotion["intitule_promotion"] ?></option>
                <?php } ?>
            </select>

            <label for="photo">Photo <span class="etoile">**</span></label>
            <input type="file" name="photo" id="photo" accept="image/png, image/jpg, image/jpeg">

            <?php if (isset($erreurs["photo"])) {?>
                <p class="erreur-validation"><?= $erreurs["photo"] ?></p>
            <?php } ?>

            <input type="submit" value="Envoyer">

            <div><span class="etoile">*</span> : Ce champ est obligatoire.</div>
            <div><span class="etoile">**</span> : Seules les images avec l'extension .png ou .jpeg ayant un poids inférieur à 600 ko sont acceptées.</div>
        </form>

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