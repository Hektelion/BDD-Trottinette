<?php

/*
louer_rendre.php

Page de confirmation de la location/rendu

*/

//Démarage session
session_start();
header('Content-type: text/html; charset=utf-8');
include('../includes/config.php');

//Connexion à la BDD et actualisation de la session
include('../includes/fonctions.php');
$id = connexionbdd();
actualiser_session($id);

//Verification que l'utilisateur est connecté
if(!isset($_SESSION['id_utilisateur']))
{
    $informations = Array( //L'utilisateur n'est pas connecté
        true,
        'Non connecté',
        'Vous devez etre connecté pour venir ici',
        ' - <a href="'.ROOTPATH.'/membres/connexion.php">Vous connectez</a>',
        ROOTPATH.'/index.php',
        5
    );

    require_once('../information.php');
    exit();
}

//Inclusion du haut de la page
include('../includes/haut.php');

//Verification que l'utilisateur n'a pas déja une location
if(isset($_SESSION['location']) && $_SESSION['location'] == 0) //LOCATION
{
    //Recuperation de :
    //  id_trottinette
    $requete = "SELECT id_trottinette FROM trottinette WHERE id_station = '" . $_POST['id_station'] . "' AND a_quai = '1' AND en_panne = '0'";
}
elseif(isset($_SESSION['location']) && $_SESSION['location'] == 1) //RENDU
{
    //Recuperation de :
    //  idLocation
    //  date_emprunt
    //  id_trottinette
    $requete = "SELECT id_location, date_emprunt, id_trottinette FROM location WHERE id_utilisateur = '" . $_SESSION['id_utilisateur'] . "' AND rendu = '0'";
}
else //ERREUR INCONNU
{
    $informations = Array(
        true,
        'Probleme $_SESSION[\'location\']',
        'Problème avec votre statut de location contactez un administrateur',
        ' - <a href="'.ROOTPATH.'/contact.php">Contactez l\'administrateur</a>',
        ROOTPATH.'/index.php',
        5
    );

    require_once('../information.php');
    exit();
}

//echo $requete;

$resultat = requete_sql($id, $requete, 1);

//echo "<br>" . $resultat['idLocation'] . "<br>";
//echo $resultat['date_emprunt'] . "<br>";
//echo $resultat['id_trottinette'] . "<br>";

//Verification que l'utilisateur n'a pas déja une location
if($_SESSION['location'] == 0) //LOCATION
{
    $requete = "INSERT INTO location (id_utilisateur, id_trottinette) VALUES ( '" . $_SESSION['id_utilisateur'] . "', '" . $resultat['id_trottinette'] . "')";
}
elseif($_SESSION['location'] == 1) //RENDU
{

    $date_rendu = date("Y-m-d-H:i:s");
    $date_emprunt_int = strtotime($resultat['date_emprunt']);
    $diff = time() - $date_emprunt_int;
    $cout = $diff /600;

    $requete = "UPDATE location SET cout = '" . $cout ."', date_rendu = '" . $date_rendu . "', rendu = '1'  WHERE id_location = '" . $resultat['id_location'] . "'";
}

//echo $requete . "<br>";

$resultat2 = requete_sql($id, $requete, 1);

//echo $resultat2;

//Verification que l'utilisateur n'a pas déja une location
if($_SESSION['location'] == 0) //LOCATION
{
    $requete = "UPDATE station SET nb_trottinette = nb_trottinette - 1 WHERE id_station = '" . $_POST['id_station'] . "'";
}
elseif($_SESSION['location'] == 1) //RENDU
{
    $requete = "UPDATE station SET nb_trottinette = nb_trottinette + 1 WHERE id_station = '" . $_POST['id_station'] . "'";
}

//echo $requete;

$resultat3 = requete_sql($id, $requete, 1);

//echo $resultat3;

//Verification que l'utilisateur n'a pas déja une location
if($_SESSION['location'] == 0) //LOCATION
{
    $requete = "UPDATE trottinette SET a_quai = '0' WHERE id_trottinette = '" . $resultat['id_trottinette'] . "'";
}
elseif($_SESSION['location'] == 1) //RENDU
{
    $requete = "UPDATE trottinette SET a_quai = '1' WHERE id_trottinette = '" . $resultat['id_trottinette'] . "'";
}

//echo $requete;

$resultat3 = requete_sql($id, $requete, 1);

//echo $resultat3;

//Verification que l'utilisateur n'a pas déja une location
if($_SESSION['location'] == 0) //LOCATION
{
    $_SESSION['location'] = 1;
    $informations = Array( //L'utilisateur a déja une location
        true,
        'Trottinette louer',
        'Vous avez louer une trottinette',
        '',
        ROOTPATH.'/index.php',
        5
    );

    require_once('../information.php');
    exit();
}
elseif($_SESSION['location'] == 1) //RENDU
{
    $_SESSION['location'] = 0;
    $informations = Array( //L'utilisateur a déja une location
        true,
        'Trottinette rendu',
        'Vous avez rendu votre torttinette cela vous coutera : ' . $cout . ' euros',
        '',
        ROOTPATH.'/index.php',
        5
    );

    require_once('../information.php');
    exit();
}

mysqli_close($id);
?>
