<?php
    session_start();
    require_once "src/modele/utilisateurs-db.php";

    $prenomUtilisateur = null;
    $nomUtilisateur = null;
    $emailUtilisateur = null;
    $motDePasse = null;
    $erreurs = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $verifMotDePasse = verifyPassword($_POST["mot-de-passe"]);

        if (empty(trim($_POST["prenom-utilisateur"]))) {
            $erreurs["prenom"] = "Le prénom est obligatoire !";
        } else {
            $prenomUtilisateur = trim($_POST["prenom-utilisateur"]);
        }

        if (empty(trim($_POST["nom-utilisateur"]))) {
            $erreurs["nom"] = "Le nom est obligatoire !";
        } else {
            $nomUtilisateur = trim($_POST["nom-utilisateur"]);
        }

        if (empty(trim($_POST["email-utilisateur"]))) {
            $erreurs["mail"] = "L'e-mail est obligatoire !";
        } elseif (!filter_var($_POST["email-utilisateur"], FILTER_VALIDATE_EMAIL)) {
            $erreurs["mail"] = "Il faut que l'adresse mail soit valide (avec un @ et un nom de domaine valide) !";
        } else {
            $emailUtilisateur = trim($_POST["email-utilisateur"]);
        }

        if (empty(trim($_POST["mot-de-passe"]))) {
            $erreurs["motDePasse"] = "Le mot de passe est obligatoire !";
        } elseif (isset($verifMotDePasse)) {
            $erreurs["motDePasse"] = $verifMotDePasse;
        } elseif (trim($_POST["mot-de-passe"]) <> trim($_POST["mot-de-passe2"])) {
            $erreurs["motDePasse2"] = "Les mots de passe sont différents !";
        } else {
            $motDePasse = trim($_POST["mot-de-passe"]);
        }

        if ($_POST["cgu"] <> "on") {
            $erreurs["cgu"] = "Vous devez accepter les Conditions Générales d'Utilisation !";
        }

        if (empty($erreurs)) {
            $hash = password_hash($motDePasse,PASSWORD_ARGON2I);
            $_SESSION["user"]["prenom-utilisateur"] = $prenomUtilisateur;
            $_SESSION["user"]["nom-utilisateur"] = $nomUtilisateur;
            $_SESSION["user"]["email-utilisateur"] = $emailUtilisateur;
            $_SESSION["user"]["mot-de-passe"] = $hash;
            addUser($nomUtilisateur,$prenomUtilisateur,$emailUtilisateur,$hash);
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
            <?php if (isset($_SESSION["user"])) { ?>
                <li><a href="<?php unset($_SESSION["user"])?>">Déconnexion</a></li>
            <?php } else { ?>
                <li><a href="connexion.php">Connexion</a></li>
            <?php } ?>
        </ul>
    </header>

    <div class="content">
        <div class="grid">
            <i class="fa-solid fa-user-plus"></i>
            <form method="post">
                <label for="prenom-utilisateur">Prénom <span class="etoile">*</span></label>
                <input type="text" name="prenom-utilisateur" id="prenom-utilisateur" value="<?= $prenomUtilisateur?>">

                <?php if (isset($erreurs["prenom"])) {?>
                    <p class="erreur-validation"><?= $erreurs["prenom"] ?></p>
                <?php } ?>

                <label for="nom-utilisateur">Nom <span class="etoile">*</span></label>
                <input type="text" name="nom-utilisateur" id="nom-utilisateur" value="<?= $nomUtilisateur?>">

                <?php if (isset($erreurs["nom"])) {?>
                    <p class="erreur-validation"><?= $erreurs["nom"] ?></p>
                <?php } ?>

                <label for="email-utilisateur">E-mail <span class="etoile">*</span></label>
                <input type="text" name="email-utilisateur" id="email-utilisateur" value="<?= $emailUtilisateur?>">

                <?php if (isset($erreurs["mail"])) {?>
                    <p class="erreur-validation"><?= $erreurs["mail"] ?></p>
                <?php } ?>

                <label for="mot-de-passe2">Mot de passe <span class="etoile">*</span></label>
                <input type="password" name="mot-de-passe" id="mot-de-passe2">

                <?php if (isset($erreurs["motDePasse"])) {?>
                    <p class="erreur-validation"><?= $erreurs["motDePasse"] ?></p>
                <?php } ?>

                <label for="mot-de-passe3">Confirmer le mot de passe <span class="etoile">*</span></label>
                <input type="password" name="mot-de-passe2" id="mot-de-passe3">

                <?php if (isset($erreurs["motDePasse2"])) {?>
                    <p class="erreur-validation"><?= $erreurs["motDePasse2"] ?></p>
                <?php } ?>

                <div class="cgu">
                    <input type="checkbox" id="cgu" name="cgu">
                    <label for="cgu">J'accepte les <a href="https://policies.google.com/terms">Conditions Générales d'Utilisation</a>.
                        <span class="etoile">*</span></label>
                </div>

                <?php if (isset($erreurs["cgu"])) {?>
                    <p class="erreur-validation"><?= $erreurs["cgu"] ?></p>
                <?php } ?>

                <div><span class="etoile">*</span> : Ce champ est obligatoire.</div>
                <input type="submit" value="S'inscrire">
            </form>
            <div class="autre">Vous avez déjà un compte ? <a href="connexion.php">Connectez-vous !</a></div>
        </div>
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