<?php
/*
haut.php

Page incluse créant le doctype

*/
?>
<!DOCTYPE html PUBLIC>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
    <?php
    /**********Vérification du titre...*************/

    if(isset($titre) && trim($titre) != '')
        $titre = $titre.' : '.TITRESITE;
    else
        $titre = TITRESITE;

    /***********Fin vérification titre...************/
    ?>
    <title><?php echo $titre; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="language" content="fr" />
    <link rel="stylesheet" title="Design" href="<?php echo ROOTPATH; ?>/design.css" type="text/css" media="screen" />
</head>


<body>
<div id="banniere">
    <a href="<?php echo ROOTPATH;?>/index.php"><img src="<?php echo ROOTPATH; ?>/images/banniere.png"/></a>
</div>

<div id="menu">
    <div id="menu_gauche">
        <a href="<?php echo ROOTPATH; ?>/contact.php">Contactez-nous</a>
        <?php
        if(isset($_SESSION['privilege']) AND $_SESSION['privilege'] > 1)
        {
            ?>
            <a href="<?php echo ROOTPATH; ?>/membres/modif_privilege.php">Modifier les droits d'un utilisateur</a>
            <?php
        }
        ?>
    </div>

    <div id="menu_centre">
        <?php
        if(isset($_SESSION['privilege']) AND $_SESSION['privilege'] > 0)
        {
            ?>
            <a href="<?php echo ROOTPATH; ?>/erreur/erreur.php">Regler les probleme</a>
            <?php
        }
        ?>
    </div>

    <div id="menu_droite">
        <?php
        if(isset($_SESSION['id_utilisateur']))
        {
            if(isset($_SESSION['location']) AND $_SESSION['location'] != 1)
            {
                ?>
                <a href="<?php echo ROOTPATH; ?>/location/afficher_station.php">Louer une trottinette</a>
                <?php
            }
            else
            {
                ?>
                <a href="<?php echo ROOTPATH; ?>/location/afficher_station.php">Rendre une trottinette</a>
                <?php
            }
            ?>
            <a href="<?php echo ROOTPATH; ?>/membres/moncompte.php">Gérer mon compte</a>
            <a href="<?php echo ROOTPATH; ?>/membres/deconnexion.php">Se déconnecter</a>
            <?php
        }

        else
        {
            ?>
            <a href="<?php echo ROOTPATH; ?>/membres/inscription.php">Inscription</a>
            <a href="<?php echo ROOTPATH; ?>/membres/connexion.php">Connexion</a>
            <?php
        }
        ?>
    </div>
</div>
