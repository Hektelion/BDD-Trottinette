<?php

/*
erreur.php

Page d'affichage des erreurs

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

?>

<!--Colonne gauche-->
<div id="colonne_gauche">
    <?php
    include('../includes/colg.php');
    ?>
</div>

<!--Contenu-->
<div id="contenu">
    <div id="map">
        <a href="../index.php">Accueil</a>
    </div>

    <?php

    if(isset($_SESSION['privilege']) && $_SESSION['privilege'] > 1) //LOCATION
    {
        $requete = "SELECT COUNT(id_erreur) AS cnt , id_erreur, id_station, id_trottinette, heure_signalement, erreur FROM erreur WHERE regler = '0' GROUP BY id_erreur";
    }
    else //ERREUR INCONNU
    {
        $informations = Array(
            true,
            'Privilege insuffisant',
            'Vous n avez pas le droit d acceder à cette partie du site',
            '',
            ROOTPATH.'/index.php',
            5
        );

        require_once('../information.php');
        exit();
    }

    //$resultat = requete_sql($id, $requete, 2);

    if( !($resultat=mysqli_query($id, $requete) ))
    {
        exit('Erreur SQL : '.mysqli_error($id). '<br>' . ' Ligne : '. __LINE__ .'.' . $requete . ' ');
    }
    queries();

    ?>
        <h1>Trottinette en panne</h1>
    <?php

    while ($ligneresultat = mysqli_fetch_array($resultat)) {
        ?>
        <form action="traitement_erreur.php" method="post" name="Location">
            <fieldset>
                <label for="id_erreur"        class="station">Erreur numéro :     <?php echo $ligneresultat[1]; ?></label>
                <label for="id_station"       class="station">id_station :        <?php echo $ligneresultat[2]; ?></label>
                <label for="id_trottinette"   class="station">id_trottinette :    <?php echo $ligneresultat[3]; ?></label>
                <label for="heure_signalement"class="station">heure_signalement : <?php echo $ligneresultat[4]; ?></label>
                <label for="erreur"           class="station">erreur :            <?php echo $ligneresultat[5]; ?></label>
                <input type="hidden" name="id_erreur" id="id_erreur" value=" <?php echo $ligneresultat[1]; ?>" />
                <input type="hidden" name="id_station" id="id_station" value=" <?php echo $ligneresultat[2]; ?>" />
                <input type="hidden" name="id_trottinette" id="id_trottinette" value=" <?php echo $ligneresultat[3]; ?>" />
                <input type="submit" value="Regler le probleme" />
            </fieldset>
        </form><br>
        <?php
    }

    $requete = "SELECT COUNT(id_station) AS cnt , id_station, nom, adresse FROM station WHERE nb_trottinette = nb_max GROUP BY id_station";

    if( !($resultat2=mysqli_query($id, $requete) ))
    {
        exit('Erreur SQL : '.mysqli_error($id). '<br>' . ' Ligne : '. __LINE__ .'.' . $requete . ' ');
    }
    queries();

    //echo $requete;

    ?>
        <h1>Station pleine</h1>
    <?php

    while ($ligneresultat = mysqli_fetch_array($resultat2)) {
        ?>
        <form action="traitement_erreur.php" method="post" name="Location">
            <fieldset>
                <label for="idStation"        class="station">Station numéro : <?php echo $ligneresultat[1]; ?></label>
                <label for="nom"              class="station">nom : <?php echo $ligneresultat[2]; ?></label>
                <label for="adresse"          class="station">adresse : <?php echo $ligneresultat[3]; ?></label>
                <input type="hidden" name="id_station" id="id_station" value="<?php echo $ligneresultat[1]; ?>" />
                <input type="submit" value="-->" />
            </fieldset>
        </form><br>
        <?php
    }

    ?>
    <!--bas-->
    <?php
    include('../includes/bas.php');
    mysqli_close($id);
    ?>
