<?php
    session_start();
    require_once "src/modele/utilisateurs-db.php";

    $users = selectAllUsers();
    $prenomUtilisateur = null;
    $nomUtilisateur = null;
    $emailUtilisateur = null;
    $motDePasse = null;
    $erreurs = [];

    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        if (empty(trim($_POST["email-utilisateur"]))) {
            $erreurs["mail"] = "L'e-mail est obligatoire !";
        } elseif (!filter_var($_POST["email-utilisateur"], FILTER_VALIDATE_EMAIL)) {
            $erreurs["mail"] = "Il faut que l'adresse mail soit valide (avec un @ et un nom de domaine valide) !";
        } else {
            foreach ($users as $user) {
                if ($user["email_utilisateur"] == trim($_POST["email-utilisateur"])) {
                    $emailUtilisateur = trim($_POST["email-utilisateur"]);
                    break;
                }
            }
            if (!isset($emailUtilisateur)) {
                $erreurs["connexion"] = "Un problème est survenu lors de l'authentification !";
            }
        }

        if (empty(trim($_POST["mot-de-passe"]))) {
            $erreurs["motDePasse"] = "Le mot de passe est obligatoire !";
        } elseif (!empty($erreurs["connexion"])) {
            $erreurs["connexion"] = "Un problème est survenu lors de l'authentification !";
        } else {
            $user = selectUserByEmail($emailUtilisateur);
            if (password_verify(trim($_POST["mot-de-passe"]),$user["mot_de_passe"])) {
                $motDePasse = trim($_POST["mot-de-passe"]);
            } else {
                $erreurs["connexion"] = "Un problème est survenu lors de l'authentification !";
            }
        }
        if (empty($erreurs)) {
            $_SESSION["user"]["prenom-utilisateur"] = $user["prenom_utilisateur"];
            $_SESSION["user"]["nom-utilisateur"] = $user["nom_utilisateur"];
            $_SESSION["user"]["email-utilisateur"] = $user["email_utilisateur"];
            $_SESSION["user"]["mot-de-passe"] = $user["mot_de_passe"];
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
        <div class="grid2">
            <i class="fa-solid fa-user-tag"></i>
            <form method="post">
                <label for="email-utilisateur2">E-mail</label>
                <input type="text" name="email-utilisateur" id="email-utilisateur2">

                <label for="mot-de-passe">Mot de passe</label>
                <input type="password" name="mot-de-passe" id="mot-de-passe">

                <?php if (isset($erreurs["mail"])) {?>
                    <p class="erreur-validation"><?= $erreurs["mail"] ?></p>
                <?php } elseif (isset($erreurs["motDePasse"])) {?>
                    <p class="erreur-validation"><?= $erreurs["motDePasse"] ?></p>
                <?php } elseif (isset($erreurs["connexion"])) {?>
                    <p class="erreur-validation"><?= $erreurs["connexion"] ?></p>
                <?php } ?>

                <input type="submit" value="Se connecter">
            </form>
            <div class="autre">Vous n'avez encore de compte ? <a href="inscription.php">Inscrivez-vous !</a></div>
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