<?php
/*
connexion.php

Permet de se connecter au site.

*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('../includes/config.php');

/********Actualisation de la session...**********/

include('../includes/fonctions.php');
$id = connexionbdd();
actualiser_session($id);

/********Fin actualisation de session...**********/

if(isset($_SESSION['id_utilisateur']))
{
    $informations = Array(/*Membre qui essaie de se connecter alors qu'il l'est déjà*/
        true,
        'Vous êtes déjà connecté',
        'Vous etes déja connecté <span class="pseudo">'.htmlspecialchars($_SESSION['identifiant'], ENT_QUOTES).'</span>.',
        ' - <a href="'.ROOTPATH.'/membres/deconnexion.php">Se déconnecter</a>',
        ROOTPATH.'/index.php',
        5
    );

    require_once('../information.php');
    exit();
}

if($_POST['validate'] != 'ok')
{
    /********Entête et titre de page*********/

    $titre = 'Connexion';

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
        <a href="../index.php">Accueil</a> => <a href="connexion.php">Connexion</a>
    </div>

    <h1>Connection</h1>
    <p>Pour vous connecter, indiquez votre pseudo et votre mot de passe.</p>

    <form name="connexion" id="connexion" method="post" action="connexion.php">
        <fieldset><legend>Connexion</legend>
            <label for="identifiant" class="float"> Identifiant :  </label> <input type="text"     name="identifiant" id="identifiant" value="<?php if(isset($_SESSION['connexion_identifiant'])) echo $_SESSION['connexion_identifiant']; ?>"/><br/>
            <label for="mdp"         class="float"> Mot de Passe : </label> <input type="password" name="mdp"         id="mdp"/><br/>
            <input type="hidden" name="validate" id="validate" value="ok"/>
            <input type="checkbox" name="cookie" id="cookie"/>
            <label for="cookie">Me connecter automatiquement à mon prochain passage.</label><br/>
            <div class="center"><input type="submit" value="Connexion" /></div>
        </fieldset>
    </form>

    <h1>Options</h1>
    <p><a href="inscription.php">Je ne suis pas inscrit !</a><br/>
        <a href="moncompte.php?action=reset">J'ai oublié mon mot de passe !</a>
    </p>
    <?php
}
else
{
    $requete = "SELECT COUNT(id_utilisateur) AS nbr, id_utilisateur, identifiant, mot_de_passe, privilege FROM utilisateur WHERE identifiant = '" . $_POST['identifiant'] . "' GROUP BY id_utilisateur";

    $result = requete_sql($id, $requete, 1);

    echo $result['nbr'] . "<br>";

    //
    if($result['nbr'] == 1)
    {
        //
        if(sha1($_POST['mdp']) == $result['mot_de_passe'])
        {
            $_SESSION['id_utilisateur'] = $result['id_utilisateur'];
            $_SESSION['identifiant'] = $result['identifiant'];
            $_SESSION['mot_de_passe'] = $result['mot_de_passe'];
            $_SESSION['privilege'] = $result['privilege'];
            a_une_location($id);
            unset($_SESSION['connexion_identifiant']);

            if(isset($_POST['cookie']) && $_POST['cookie'] == 'on')
            {
                setcookie('id_utilisateur', $result['id_utilisateur'], time()+365*24*3600);
                setcookie('mot_de_passe', $result['mot_de_passe'], time()+365*24*3600);
            }

            $informations = Array(/*Vous êtes bien connecté*/
                false,
                'Connexion réussie',
                'Vous etes connecté en tant que <span class="identifiant">'.htmlspecialchars($_SESSION['identifiant'], ENT_QUOTES).'</span>.',
                '',
                ROOTPATH.'/index.php',
                3
            );
            require_once('../information.php');
            exit();
        }

        else
        {
            $_SESSION['connexion_identifiant'] = $_POST['identifiant'];
            $informations = Array(/*Erreur de mot de passe*/
                true,
                'Mauvais mot de passe',
                'Vous avez fourni un mot de passe incorrect.',
                ' - <a href="'.ROOTPATH.'/index.php">Index</a>',
                ROOTPATH.'/membres/connexion.php',
                3
            );
            require_once('../information.php');
            exit();
        }
    }

    else if($result['nbr'] > 1)
    {
        $informations = Array(/*Erreur d identifiant doublon (normalement impossible)*/
            true,
            'Doublon',
            'Deux membres ou plus ont le même identifiant, contactez un administrateur pour régler le problème.',
            ' - <a href="'.ROOTPATH.'/index.php">Index</a>',
            ROOTPATH.'/contact.php',
            3
        );
        require_once('../information.php');
        exit();
    }

    else
    {
        $informations = Array(/*identifiant inconnu*/
            true,
            'identifiant inconnu',
            'L identifiant <span class="identifiant">'.htmlspecialchars($_POST['identifiant'], ENT_QUOTES).'</span> n\'existe pas dans notre base de données. Vous avez probablement fait une erreur.',
            ' - <a href="'.ROOTPATH.'/index.php">Index</a>',
            ROOTPATH.'/membres/connexion.php',
            5
        );
        require_once('../information.php');
        exit();
    }
}
?>
    </div>

<?php
include('../includes/bas.php');
mysqli_close($id);
?>