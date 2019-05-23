<?php
/*
index.php

Index du site.

*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('includes/config.php');

/********Actualisation de la session...**********/

include('includes/fonctions.php');
$id = connexionbdd();
actualiser_session($id);

/********Fin actualisation de session...**********/

/********Entête et titre de page*********/

$titre = 'Inscription';

include('includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/

?>

    <div id="colonne_gauche">
        <?php include('includes/colg.php'); ?>
    </div>

    <div id="contenu">
        <div id="map">
            <a href="index.php">Accueil</a>
        </div>

        <h1>Bienvenue sur LocationTrottinette</h1>
        <p>Vous devez etre inscrit pour louer ou rendre une trottinette -> <a href="membres/inscription.php">s'inscrire</a>.<br>
            Le tarif est de 10 centimes/minutes.
        </p>
    </div>

<?php
    include('includes/bas.php');
    mysqli_close($id);
?>