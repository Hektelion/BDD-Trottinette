<?php

/*
afficher_station.php

Affichage des station ayant des trottinette à disposition

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

    <h1>Station disponible</h1>
    <p>Bienvenue dans la zone location, choississez la trottinette que vous souhaitez louer<br/></p>
    <?php

//Verification que l'utilisateur n'a pas déja une location
if(isset($_SESSION['location']) && $_SESSION['location'] == 0) //LOCATION
{
    $requete = "SELECT COUNT(id_station) AS cnt , id_station, nom, adresse, nb_max, nb_trottinette FROM station WHERE nb_trottinette != '0' GROUP BY id_station";
}
elseif(isset($_SESSION['location']) && $_SESSION['location'] == 1) //RENDU
{
    $requete = "SELECT COUNT(id_station) AS cnt , id_station, nom, adresse, nb_max, nb_trottinette FROM station WHERE nb_trottinette != nb_max GROUP BY id_station";
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

//$resultat = requete_sql($id, $requete, 2);

    if( !($resultat=mysqli_query($id, $requete) ))
    {
        exit('Erreur SQL : '.mysqli_error($id). '<br>' . ' Ligne : '. __LINE__ .'.' . $requete . ' ');
    }
    queries();

if (mysqli_num_rows($resultat) == 0) {
    $informations = Array(
        true,
        'Toutes les station sont vide ou pleine',
        'Aucune station disponible',
        '',
        ROOTPATH.'/index.php',
        5
    );

    require_once('../information.php');
    exit();
}
else {
    while ($ligneresultat = mysqli_fetch_array($resultat)) {
        ?>
        <form action="louer_rendre.php" method="post" name="Location">
            <fieldset>
                <label for="idStation"        class="station">Station numéro : <?php echo $ligneresultat[1]; ?></label>
                <label for="nom"              class="station">nom : <?php echo $ligneresultat[2]; ?></label>
                <label for="adresse"          class="station">adresse : <?php echo $ligneresultat[3]; ?></label>
                <label for="nbTrottinetteMax" class="station">Contenance : <?php echo $ligneresultat[4]; ?></label>
                <label for="nbTrottinette"    class="station">Nombre de trottinettes : <?php echo $ligneresultat[5]; ?></label>
                <input type="hidden" name="id_station" id="id_station" value="<?php echo $ligneresultat[1]; ?>" />
                <input type="submit" value="-->" />
            </fieldset>
        </form><br>
        <?php
    }
}

    ?>
<!--bas-->
<?php
include('../includes/bas.php');
mysqli_close($id);
?>