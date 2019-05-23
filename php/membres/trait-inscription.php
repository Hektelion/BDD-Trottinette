<?php
/*
trait-inscription.php

Permet de valider son inscription.

*/

session_start();
header('Content-type: text/html; charset=utf-8');
//Inclusion du fichier config.php
include('../includes/config.php');

/********Actualisation de la session...**********/

//Inclusion du fichier fonctions.php
include('../includes/fonctions.php');
$id = connexionbdd();
actualiser_session($id);

/********Fin actualisation de session...**********/

if(isset($_SESSION['id_utilisateur']))
{
    header('Location: '.ROOTPATH.'/index.php');
    exit();
}

if($_SESSION['inscrit'] == $_POST['identifiant'] && trim($_POST['inscrit']) != '')
{
    $informations = Array(/*Déjà inscrit (en cas de bug...)*/
        true,
        'Vous êtes déjà inscrit',
        'Vous avez déjà complété une inscription avec le identifiant <span class="identifiant">'.htmlspecialchars($_SESSION['inscrit'], ENT_QUOTES).'</span>.',
        ' - <a href="'.ROOTPATH.'/index.php">Retourner à l\'index</a>',
        ROOTPATH.'/membres/connexion.php',
        5
    );
    require_once('../information.php');
    exit();
}

/********Étude du bazar envoyé***********/

//Mise à zero de erreurs
$_SESSION['erreurs'] = 0;

//echo "Nombre d'erreur : " . $_SESSION['erreurs'] . "<br>";

//identifiant
//Si un identifiant a été reçu
if(isset($_POST['identifiant']))
{
    //Récupération du identifiant en supprimant les espaces
    $identifiant = trim($_POST['identifiant']);

    //Verification de identifiant à l'aide de la fonction verification_identifiant
    $identifiant_result = verification_identifiant($id, $identifiant);

    //Si l'identifiant est trop court
    if($identifiant_result == 'tooshort')
    {
        $_SESSION['identifiant_info'] = '<span class="erreur">L identifiant '.htmlspecialchars($identifiant, ENT_QUOTES).' est trop court (minimum 6 caractères).</span><br/>';
        $_SESSION['form_identifiant'] = '';
        $_SESSION['erreurs']++;
    }

    //Si l'identifiant est trop long
    else if($identifiant_result == 'toolong')
    {
        $_SESSION['identifiant_info'] = '<span class="erreur">L identifiant '.htmlspecialchars($identifiant, ENT_QUOTES).' est trop long (maximum 32 caractères).</span><br/>';
        $_SESSION['form_identifiant'] = '';
        $_SESSION['erreurs']++;
    }

    //Si l'identifiant existe déja
    else if($identifiant_result == 'exists')
    {
        $_SESSION['identifiant_info'] = '<span class="erreur">L identifiant '.htmlspecialchars($identifiant, ENT_QUOTES).' est déjà pris.</span><br/>';
        $_SESSION['form_identifiant'] = '';
        $_SESSION['erreurs']++;
    }

    else if($identifiant_result == 'empty')
    {
        $_SESSION['identifiant_info'] = '<span class="erreur">Aucun identifiant.</span><br/>';
        $_SESSION['form_identifiant'] = '';
        $_SESSION['erreurs']++;
    }

    //Si l'identifiant est ok
    else if($identifiant_result == 'ok')
    {
        $_SESSION['identifiant_info'] = '';
        $_SESSION['form_identifiant'] = $identifiant;
    }
}

else
{
    header('Location: ../index.php');
    exit();
}

//echo "Nombre d'erreur : " . $_SESSION['erreurs'] . "<br>";

//Mot de passe
//Si un mot de passe a été reçu
if(isset($_POST['mdp']))
{
    $mdp = trim($_POST['mdp']);
    $mdp_result = verification_mot_de_passe($mdp);

    //Si le mot de passe est trop petit
    if($mdp_result == 'tooshort')
    {
        $_SESSION['mdp_info'] = '<span class="erreur">Le mot de passe entré est trop court (minimum 6 caractères).</span><br/>';
        $_SESSION['form_mdp'] = '';
        $_SESSION['erreurs']++;
    }

    //Si le mot de passe est trop long
    else if($mdp_result == 'toolong')
    {
        $_SESSION['mdp_info'] = '<span class="erreur">Le mot de passe entré est trop long (maximum 50 caractères)</span><br/>';
        $_SESSION['form_mdp'] = '';
        $_SESSION['erreurs']++;
    }

    //Si le mot de passe ne contient pas de chiffe
    else if($mdp_result == 'nofigure')
    {
        $_SESSION['mdp_info'] = '<span class="erreur">Votre mot de passe doit contenir au moins un chiffre.</span><br/>';
        $_SESSION['form_mdp'] = '';
        $_SESSION['erreurs']++;
    }

    //Si le mot de passe ne contient pas de majuscule
    else if($mdp_result == 'noupcap')
    {
        $_SESSION['mdp_info'] = '<span class="erreur">Votre mot de passe doit contenir au moins une majuscule.</span><br/>';
        $_SESSION['form_mdp'] = '';
        $_SESSION['erreurs']++;
    }

    //Si le mot de passe est vide
    else if($mdp_result == 'empty')
    {
        $_SESSION['mdp_info'] = '<span class="erreur">Vous n\'avez pas entré de mot de passe.</span><br/>';
        $_SESSION['form_mdp'] = '';
        $_SESSION['erreurs']++;

    }

    //Si le mot de passe est ok
    else if($mdp_result == 'ok')
    {
        $_SESSION['mdp_info'] = '';
        $_SESSION['form_mdp'] = $mdp;
    }


}

else
{
    header('Location: ../index.php');
    exit();
}

//echo "Nombre d'erreur : " . $_SESSION['erreurs'] . "<br>";

//Mot de passe suite
if(isset($_POST['verif_mdp']))
{
    $verif_mdp = trim($_POST['verif_mdp']);
    $verif_mdp_result = verification_mot_de_passe_egaux($verif_mdp, $mdp);

    //Si les 2 mots de passe sont different
    if($verif_mdp_result == 'different')
    {
        $_SESSION['verif_mdp_info'] = '<span class="erreur">Le mot de passe de vérification est different du mot de passe</span><br/>';
        $_SESSION['form_verif_mdp'] = '';
        $_SESSION['erreurs']++;
        if(isset($_SESSION['form_mdp'])) unset($_SESSION['form_mdp']);
    }
    else
    {
        if($verif_mdp_result == 'ok')
        {
            $_SESSION['verif_mdp_info'] = '';
            $_SESSION['form_verif_mdp'] = $verif_mdp;
        }
        else
        {
            $_SESSION['verif_mdp_info'] = str_replace('passe', 'passe de vérification', $_SESSION['mdp_info']);
            $_SESSION['form_verif_mdp'] = '';
            $_SESSION['erreurs']++;
        }
    }
}

else
{
    header('Location: ../index.php');
    exit();
}

//echo "Nombre d'erreur : " . $_SESSION['erreurs'] . "<br>";

//Nom
if(isset($_POST['nom']))
{
    $nom = trim($_POST['nom']);
    $nom_result = verification_nom($nom);

    //
    if($nom_result == 'ok')
    {
        $_SESSION['nom_info'] = '';
        $_SESSION['form_nom'] = $nom;
    }
    else
    {
        $_SESSION['nom_info'] = '<span class="erreur">Le nom est non renseigné</span><br/>';
        $_SESSION['form_nom'] = '';
    }
}

else
{
    header('Location: ../index.php');
    exit();
}

//echo "Nombre d'erreur : " . $_SESSION['erreurs'] . "<br>";

//Prenom
if(isset($_POST['prenom']))
{
    $prenom = trim($_POST['prenom']);
    $prenom_result = verification_prenom($prenom);

    //
    if($prenom_result == 'ok')
    {
        $_SESSION['prenom_info'] = '';
        $_SESSION['form_prenom'] = $prenom;
    }
    else
    {
        $_SESSION['prenom_info'] = '<span class="erreur">Le prenom est non renseigné</span><br/>';
        $_SESSION['form_prenom'] = '';
    }
}

else
{
    header('Location: ../index.php');
    exit();
}

//echo "Nombre d'erreur : " . $_SESSION['erreurs'] . "<br>";

//Adresse
if(isset($_POST['adresse']))
{
    $adresse = trim($_POST['adresse']);
    $adresse_result = verification_adresse($adresse);

    //
    if($adresse_result == 'ok')
    {
        $_SESSION['adresse_info'] = '';
        $_SESSION['form_adresse'] = $adresse;
    }
    else
    {
        $_SESSION['adresse_info'] = '<span class="erreur">L adresse est non renseigné</span><br/>';
        $_SESSION['form_adresse'] = '';
    }
}

else
{
    header('Location: ../index.php');
    exit();
}

//echo "Nombre d'erreur : " . $_SESSION['erreurs'] . "<br>";


$nom = trim($_POST['nom']);
$prenom = trim($_POST['prenom']);
$adresse = trim($_POST['adresse']);

/*************Fin étude******************/

/********Entête et titre de page*********/
if($_SESSION['erreurs'] > 0)
    $titre = 'Erreur : Inscription 2/2';
else
    $titre = 'Inscription 2/2';

include('../includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

<div id="colonne_gauche">
    <?php
    include('../includes/colg.php');
    ?>
</div>

<div id="contenu">
    <div id="map">
        <!-- Absence de lien à Inscription 2/2 volontaire -->
        <a href="../index.php">Accueil</a> => Inscription 2/2
    </div>

    <!--Test des erreurs et envoi-->
    <?php
			if($_SESSION['erreurs'] == 0)
			{

                $sha_mdp = sha1($mdp);

                /*
                echo "identifiant : " . $identifiant . "<br>";
                echo "mdp : " . $mdp . "<br>";
                echo "sha_mdp : " . $sha_mdp . "<br>";
                echo "nom : " . $nom . "<br>";
                echo "prenom : " . $prenom . "<br>";
                echo "adresse : " . $adresse . "<br>";
                */

                $insertion = "INSERT INTO utilisateur (identifiant, mot_de_passe, nom, prenom, adresse) VALUES ('$identifiant', '$sha_mdp', '$nom', '$prenom', '$adresse')";

				if(mysqli_query($id, $insertion))
				{
					$queries++;
					vidersession();
					$_SESSION['inscrit'] = $identifiant;
					/*informe qu'il s'est déjà inscrit s'il actualise, si son navigateur
					bugue avant l'affichage de la page et qu'il recharge la page, etc.*/
				?>

                <h1>Inscription succes !</h1>
                <p>
                    Vous etes maintenant inscrit sur le site<br/>
                    Vous pouvez vous connectez avec vos identifiant et louer une trottinette <a href="connexion.php">ici</a>.
                </p>

				<?php
				}
				else
				{

					if(stripos(mysqli_error($id), $_SESSION['form_identifiant']) !== FALSE) // recherche du identifiant
					{
						unset($_SESSION['form_identifiant']);
						$_SESSION['identifiant_info'] = '<span class="erreur">L identifiant '.htmlspecialchars($identifiant, ENT_QUOTES).' est déja utilisé</span><br/>';
						$_SESSION['erreurs']++;
					}
                    if($_SESSION['erreurs'] == 0)
                    {
                        echo "plantage SQL <br>";
                        $sqlbug = true; //plantage SQL.
                        $_SESSION['erreurs']++;
                    }
				}

			}
    ?>

    <?php
    if($_SESSION['erreurs'] > 0)
    {
        if($_SESSION['erreurs'] == 1) $_SESSION['nb_erreurs'] = '<span class="erreur">Il y a eu 1 erreur.</span><br/>';
        else $_SESSION['nb_erreurs'] = '<span class="erreur">Il y a eu '.$_SESSION['erreurs'].' erreurs.</span><br/>';
        ?>
        <h1>Inscription non validée.</h1>
        <p>Vous avez des erreurs dans votre formulaire d'inscription<br/>
        <?php
        echo $_SESSION['nb_erreurs'];
        echo $_SESSION['identifiant_info'];
        echo $_SESSION['mdp_info'];
        echo $_SESSION['verif_mdp_info'];
        echo $_SESSION['nom_info'];
        echo $_SESSION['prenom_info'];
        echo $_SESSION['adresse_info'];

        if($sqlbug !== true)
        {
            ?>
            Nous vous invitons donc à recommencer la demarche</p>
            <div class="center"><a href="inscription.php">Retour</a></div>
            <?php
        }

        else
        {
            ?>
            Une erreur inconnu c'est produite contactez un administrateur</p>
            <div class="center"><a href="inscription.php">Retenter une inscription</a> - <a href="../contact.php">Contactez-nous</a></div>
            <?php
        }
    }
    ?>
</div>

<?php
include('../includes/bas.php');
mysqli_close($id);
?>
<!--fin-->
