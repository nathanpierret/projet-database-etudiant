<?php
    session_start();
    if (isset($_GET["deco"])) {
        unset($_SESSION["user"]);
        header("Location: contacts.php");
    } else {
        require_once "src/modele/horaires-db.php";
        require_once "src/modele/contacts-db.php";
        require_once "src/outils/dates.php";

        $horaires = selectAllTimeAreas();

        $destinataire = "secretariat.best-students@ac-besancon.fr";
        $prenom = null;
        $nom = null;
        $mail = null;
        $telephone = null;
        $motif = null;
        $message = null;
        $erreurs = [];
        $_SESSION["last-visited"] = "contacts.php";

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

            if (empty(trim($_POST["motif"]))) {
                $erreurs["motif"] = "L'objet du mail est obligatoire !";
            } else {
                $motif = trim($_POST["motif"]);
            }

            if (empty(trim($_POST["message"]))) {
                $erreurs["message"] = "Le mail doit contenir un message !";
            } else {
                $message = trim($_POST["message"]);
            }

            if (empty($erreurs)) {
                $entetes = [
                    "from" => $mail,
                    "content-type" => "text/plain; charset=utf-8"
                ];
                if (mail($destinataire, $_POST["motif"], $_POST["message"], $entetes)) {
                    addMail($prenom, $nom, $mail, $telephone, $motif, $message);
                } else {
                    echo "Une erreur est survenue.";
                }
            }
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
                <li><a href="contacts.php?deco=ok">Déconnexion</a></li>
            <?php } else { ?>
                <li><a href="connexion.php">Connexion</a></li>
            <?php } ?>
        </ul>
    </header>

    <div class="content4">

        <div class="intro-contact">
            <div class="intro1">
                <h2>Besoin de nous contacter ? On est là pour vous.</h2>
                <div class="contacter">Si vous avez des questions ou besoin d'aide, vous pouvez nous contacter : </div>
                <ul class="ul">
                    <li>En envoyant un courriel à <span class="lien">secretariat.best-students@ac-besancon.fr</span></li>
                    <li>En appelant au <span class="lien">03 64 53 48 16</span> pendant les horaires du secrétariat</li>
                    <li>En écrivant une lettre au <span class="lien">16 Rue des étoiles 25000 Besançon</span></li>
                    <li>Ou en remplissant le formulaire ci-dessous</li>
                </ul>
            </div>

            <div class="intro2">
                <h2>Horaires d'ouvertures du secrétariat : </h2>
                <div class="horaires">
                    <?php foreach ($horaires as $horaire) { ?>
                        <div class="horaire">
                            <?php if (date("N") == $horaire["id_jour"]) {
                                if (($horaire["heure_debut_matin"] <= date("H:i") and $horaire["heure_fin_matin"] > date("H:i")) or ($horaire["heure_debut_apres_midi"] <= date("H:i") and $horaire["heure_fin_apres_midi"] > date("H:i"))) { ?>
                                    <div class="aujourdhui-ouvert"><?= $horaire["libelle_jour"] ?> : <?= formaterHeure($horaire)?></div>
                                <?php } else { ?>
                                    <div class="aujourdhui-ferme"><?= $horaire["libelle_jour"] ?> : <?= formaterHeure($horaire)?></div>
                                <?php }
                            } else { ?>
                                <div><?= $horaire["libelle_jour"] ?> : <?= formaterHeure($horaire)?></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" class="form2">

            <label for="prenom">Prénom <span class="etoile">*</span></label>
            <label for="nom">Nom <span class="etoile">*</span></label>

            <input type="text" name="prenom" id="prenom" value="<?= $prenom?>">
            <input type="text" name="nom" id="nom" value="<?= $nom?>">

            <?php if (isset($erreurs["prenom"]) and isset($erreurs["nom"])) {?>
                <p class="erreur-validation2"><?= $erreurs["prenom"] ?></p>
                <p class="erreur-validation3"><?= $erreurs["nom"] ?></p>
            <?php } else {
            if (isset($erreurs["prenom"])) {?>
                <p class="erreur-validation2"><?= $erreurs["prenom"] ?></p>
                <div class="vide-erreur1"></div>
            <?php } ?>
            <?php if (isset($erreurs["nom"])) {?>
                <div class="vide-erreur2"></div>
                <p class="erreur-validation3"><?= $erreurs["nom"] ?></p>
            <?php }
            }?>

            <label for="mail">E-mail <span class="etoile">*</span></label>
            <label for="telephone">Téléphone <span class="etoile">*</span></label>

            <input type="text" name="mail" id="mail" value="<?= $mail ?>">
            <input type="text" name="telephone" id="telephone" value="<?= $telephone ?>">

            <?php if (isset($erreurs["mail"]) and isset($erreurs["telephone"])) {?>
                <p class="erreur-validation2"><?= $erreurs["mail"] ?></p>
                <p class="erreur-validation3"><?= $erreurs["telephone"] ?></p>
            <?php } else {
            if (isset($erreurs["mail"])) {?>
                <p class="erreur-validation2"><?= $erreurs["mail"] ?></p>
                <div class="vide-erreur1"></div>
            <?php } ?>
            <?php if (isset($erreurs["telephone"])) {?>
                <div class="vide-erreur2"></div>
                <p class="erreur-validation3"><?= $erreurs["telephone"] ?></p>
            <?php }
            }?>

            <label for="motif" class="motif">Motif <span class="etoile">*</span></label>
            <input type="text" name="motif" id="motif" value="<?= $motif?>" class="motif">
            <?php if (isset($erreurs["motif"])) {?>
                <p class="erreur-validation2"><?= $erreurs["motif"] ?></p>
                <div class="vide-erreur1"></div>
            <?php } ?>

            <label for="message" class="message">Message <span class="etoile">*</span></label>
            <textarea name="message" id="message" cols="50" rows="10"><?= htmlspecialchars($message)?></textarea>

            <?php if (isset($erreurs["message"])) { ?>
                <p class="erreur-validation2"><?= $erreurs["message"]?></p>
                <div class="vide-erreur1"></div>
            <?php } ?>

            <input type="submit" value="Envoyer">

            <div class="requis"><span class="etoile">*</span> : Ce champ est obligatoire.</div>
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