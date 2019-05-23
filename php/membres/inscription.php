<?php
/*
inscription.php

Permet de s'inscrire sur le site

*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('../includes/config.php');

include('../includes/fonctions.php');
$id = connexionbdd();
actualiser_session($id);

if(isset($_SESSION['id_utilisateur']))
{
    header('Location: '.ROOTPATH.'/index.php');
    exit();
}

$titre = 'Inscription 1/2';

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
        <a href="../index.php">Accueil</a> => <a href="inscription.php">Inscription 1/2</a>
    </div>

    <?php
    if($_SESSION['erreurs'] > 0)
    {
        ?>
        <div class="border-red">
            <h1>Note :</h1>
            <p>
                Voici les erreurs qui ce sont produites :<br/>
                <?php
                echo $_SESSION['nb_erreurs'];
                echo $_SESSION['identifiant_info'];
                echo $_SESSION['mdp_info'];
                echo $_SESSION['verif_mdp_info'];
                echo $_SESSION['nom_info'];
                echo $_SESSION['prenom_info'];
                echo $_SESSION['adresse_info'];
                ?>
            </p>
        </div>
        <?php
    }
    ?>

    <h1>Formulaire d'inscription</h1>
    <p>Veuillez remplir le formulaire</p>
    <form action="trait-inscription.php" method="post" name="Inscription">
        <fieldset><legend>Identifiants</legend>
            <label for="identifiant" class="float">Identifiant :              </label> <input type="text"     name="identifiant" id="identifiant" size="30" value="<?php if($_SESSION['identifiant_info'] == '') echo htmlspecialchars($_SESSION['form_identifiant'],    ENT_QUOTES) ; ?>" /> <em>(entre 6 et 32 caractères)</em><br/>
            <label for="mdp"         class="float">Mot de passe :             </label> <input type="password" name="mdp"         id="mdp"         size="30" value="<?php if($_SESSION['mdp_info'] == '')         echo htmlspecialchars($_SESSION['form_mdp'],            ENT_QUOTES) ; ?>" /> <em>(entre 6 et 50 caractères)</em><br/>
            <label for="verif_mdp"   class="float">Verification mot de passe :</label> <input type="password" name="verif_mdp"   id="verif_mdp"   size="30" value="<?php if($_SESSION['verif_mdp_info'] == '')   echo htmlspecialchars($_SESSION['form_verif_mdp'],      ENT_QUOTES) ; ?>" /><br/>
            <label for="nom"         class="float">Nom :                      </label> <input type="text"     name="nom"         id="nom"         size="30" value="<?php if($_SESSION['nom_info'] == '')         echo htmlspecialchars($_SESSION['form_nom'],            ENT_QUOTES) ; ?>" /> <br/>
            <label for="prenom"      class="float">Prenom :                   </label> <input type="text"     name="prenom"      id="prenom"      size="30" value="<?php if($_SESSION['prenom_info'] == '')      echo htmlspecialchars($_SESSION['form_prenom'],         ENT_QUOTES) ; ?>" /><br/>
            <label for="adresse"     class="float">Adresse :                  </label> <input type="text"     name="adresse"     id="adresse"     size="30" value="<?php if($_SESSION['adresse_info'] == '')     echo htmlspecialchars($_SESSION['form_adresse'],        ENT_QUOTES) ; ?>" /> <br/>
        </fieldset>
        <div class="center"><input type="submit" value="Inscription" /></div>
    </form>
</div>

<!--bas-->
<?php
include('../includes/bas.php');
mysqli_close($id);
?>
