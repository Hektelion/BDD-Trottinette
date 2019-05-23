<?php
/*
connexion.php

Permet de se deconnecter au site.

*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('../includes/config.php');

/********Actualisation de la session...**********/

include('../includes/fonctions.php');
$id = connexionbdd();
actualiser_session($id);
mysqli_close($id);

/********Fin actualisation de session...**********/

if(!isset($_SESSION['id_utilisateur'])){
    $informations = Array( //Membre déja deconnecté
        true,
        'Vous etes déja deconnecté',
        'Une erreur c est produite vous etes deja deconnecté',
        ' - <a href="'.ROOTPATH.'/index.php">Index</a>',
        ROOTPATH.'/index.php',
        5
    );
    require_once('../information.php');
    exit();
}
else{
    vider_cookie();
    session_destroy();
    $informations = Array( //Le membre est deconnecté
        true,
        'Vous etes deconnecté',
        'A bientot',
        ' - <a href="'.ROOTPATH.'/index.php">Index</a>',
        ROOTPATH.'/index.php',
        5
    );
    require_once('../information.php');
    exit();
}
?>
