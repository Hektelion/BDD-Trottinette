<?php
/*
fonctions.php

Contient les fonctions usuelle du site

Liste des fonctions :
--------------------------
requete_sql($requete,$option)
queries()
connexionbdd()
actualiser_session()
vider_cookie()
verification_identifiant()
verification_mot_de_passe()
verification_mot_de_passe_egaux()
verification_nom()
verification_prenom()
verification_adresse()
vidersession()
a_une_location()
nb_trottinette_louer()
nb_trottinette_en_panne()
nb_probleme_regler()
nb_probleme_non_regler()
*/

/*
Fonction requete_sql() execute la requete passé en parametre
2 modes :
 - $option = 1 : Une seul entré retourné par sql
 - $option = 2 : Si plusieurs ou nombre d'entré retourné inconnu

*/
function requete_sql($id, $requete, $option)
{
    if( !($query = mysqli_query($id, $requete) ))
    {
        exit('Erreur SQL : '.mysqli_error($id). '<br>' . ' Ligne : '. __LINE__ .'.' . $requete . ' ');
    }
    queries();

    if($option == 1)
    {
        $query1 = mysqli_fetch_array($query);
        mysqli_free_result($query);
        return $query1;
    }

    else if($option == 2)
    {
        while($query1 = mysqli_fetch_array($query))
        {
            $query2[] = $query1;
        }
        mysqli_free_result($query);
        return $query2;
    }

    else //Erreur
    {
        exit('Argument de requete_sql non renseigné ou incorrect.');
    }
}

/*
Fonction

*/
function queries($num = 1)
{
    global $queries;
    $queries = $queries + intval($num);
}

/*
Fonction

*/
function connexionbdd()
{
    //Définition des variables de connexion à la base de données
    $bd_nom_serveur='localhost';
    $bd_login='projetBDD';
    $bd_mot_de_passe='trotinette2019';
    $bd_nom_bd='Trotinette';

    //Connexion à la base de données
    $id = mysqli_connect($bd_nom_serveur, $bd_login, $bd_mot_de_passe, $bd_nom_bd);
    mysqli_query($id, "set names 'utf8'");

    return $id;
}

/*
Fonction

*/
function actualiser_session($id)
{
    //Si La variable superglobal $_SESSION contient 'membre_id' ET la valeur numérique entière de intval($_SESSION['id_utilisateur']) est différente de 0
    //Si l'utilisateur existe et sont id est non vide
    if (isset($_SESSION['id_utilisateur']) && intval($_SESSION['id_utilisateur']) != 0) //Vérification id
    {
        $requete = "SELECT id_utilisateur, identifiant, mot_de_passe, privilege FROM utilisateur WHERE id_utilisateur = " . intval($_SESSION['id_utilisateur']) . " ;";

        //utilisation de la fonction requete_sql, on sait qu'on aura qu'un résultat car l'id d'un membre est unique.
        $retour = requete_sql($id, $requete, 1);

        //Si la requête a un résultat (c'est-à-dire si l'id existe dans la table membres)
        if (isset($retour['identifiant']) && $retour['identifiant'] != '') {
            //Si le mot de passe est incorrect
            if ($_SESSION['mot_de_passe'] != $retour['mot_de_passe']) {
                $informations = Array(/*Mot de passe de session incorrect*/
                    true,
                    'Session invalide',
                    'Mot de passe incorrect veuillez vous reconnectez.',
                    '',
                    'membres/connexion.php',
                    3
                );
                require_once('../information.php');
                vider_cookie();
                session_destroy();
                exit();
            } else {
                //Validation de la session.
                $_SESSION['id_utilisateur'] = $retour['id_utilisateur'];
                $_SESSION['identifiant'] = $retour['identifiant'];
                $_SESSION['mot_de_passe'] = $retour['mot_de_passe'];
                $_SESSION['privilege'] = $retour['privilege'];
                a_une_location($id);
            }
        }
    }
    else //On vérifie les cookies et sinon pas de session
    {
        //Si dans la variable superglobale $_COOKIE , id_utilisateur ET mot_de_passe sont défini
        if (isset($_COOKIE['id_utilisateur']) && isset($_COOKIE['mot_de_passe']))
        {
            //Si membre_id est non vide
            if (intval($_COOKIE['id_utilisateur']) != 0) {
                //idem qu'avec les $_SESSION
                $requete = "SELECT id_utilisateur, identifiant, mot_de_passe, privilege FROM utilisateur WHERE id_utilisateur = " . intval($_SESSION['id_utilisateur']) . " ;";

                //utilisation de la fonction requete_sql, on sait qu'on aura qu'un résultat car l'id d'un membre est unique.
                $retour = requete_sql($id, $requete, 1);

                if (isset($retour['identifiant']) && $retour['identifiant'] != '') {
                    if ($_COOKIE['mot_de_passe'] != $retour['mot_de_passe']) {
                        //Dehors vilain tout moche !
                        $informations = Array(/*Mot de passe de cookie incorrect*/
                            true,
                            'Mot de passe cookie erroné',
                            'Le mot de passe conservé sur votre cookie est incorrect vous devez vous reconnecter.',
                            '',
                            'membres/connexion.php',
                            3
                        );
                        require_once('../information.php');
                        vider_cookie();
                        session_destroy();
                        exit();
                    } else {
                        //La connexion a réussi
                        $_SESSION['id_utilisateur'] = $retour['id_utilisateur'];
                        $_SESSION['identifiant'] = $retour['identifiant'];
                        $_SESSION['mot_de_passe'] = $retour['mot_de_passe'];
                        $_SESSION['privilege'] = $retour['privilege'];
                    }
                }

            }
            else //cookie invalide, erreur plus suppression des cookies.
            {
                $informations = Array(/*L'id de cookie est incorrect*/
                    true,
                    'Cookie invalide',
                    'Le cookie conservant votre id est corrompu, il va donc être détruit vous devez vous reconnecter.',
                    '',
                    'membres/connexion.php',
                    3
                );
                require_once('../information.php');
                vider_cookie();
                session_destroy();
                exit();
            }
        } else {
            //Fonction de suppression de toutes les variables de cookie.
            if (isset($_SESSION['id_utilisateur'])) unset($_SESSION['id_utilisateur']);
            vider_cookie();
        }
    }
}

/*
Fonction

*/
function vider_cookie()
{
    foreach ($_COOKIE as $cle => $element) {
        setcookie($cle, '', time() - 3600);
    }
}

/*
Fonction

*/
function verification_identifiant($id, $identifiant)
{
    if($identifiant == '') return 'empty';
    else if(strlen($identifiant) < 6) return 'tooshort';
    else if(strlen($identifiant) > 32) return 'toolong';

    else
    {
        $requete = "SELECT COUNT(*) AS exist FROM utilisateur WHERE identifiant = '" . $identifiant . "';";

        $resultat = requete_sql($id, $requete, 1);

        global $queries;
        $queries++;

        if($resultat['exist'] > 0) return 'exists';
        else return 'ok';
    }
}

/*
Fonction

*/
function verification_mot_de_passe($mdp)
{
    if($mdp == '') return 'empty';
    else if(strlen($mdp) < 6) return 'tooshort';
    else if(strlen($mdp) > 50) return 'toolong';

    else
    {
        if(!preg_match('#[0-9]{1,}#', $mdp)) return 'nofigure';
        else if(!preg_match('#[A-Z]{1,}#', $mdp)) return 'noupcap';
        else return 'ok';
    }
}

/*
Fonction

*/
function verification_mot_de_passe_egaux($mdp, $mdp2)
{
    if($mdp != $mdp2 && $mdp != '' && $mdp2 != '') return 'different';
    else return verification_mot_de_passe($mdp);
}

/*
Fonction

*/
function verification_nom($nom){
    if($nom == '') return 'empty';
    else return 'ok';
}

/*
Fonction

*/
function verification_prenom($prenom){
    if($prenom == '') return 'empty';
    else return 'ok';
}

/*
Fonction

*/
function verification_adresse($adresse){
    if($adresse == '') return 'empty';
    else return 'ok';
}

/*
Fonction

*/
function vidersession()
{
    foreach($_SESSION as $cle => $element)
    {
        unset($_SESSION[$cle]);
    }
}

/*
Fonction

*/
function a_une_location($id){
    if (isset($_SESSION['id_utilisateur']) && intval($_SESSION['id_utilisateur']) != 0)
    {
        $requete = "SELECT COUNT(id_location) AS nbr FROM location WHERE id_utilisateur = '" . $_SESSION['id_utilisateur'] . "' AND rendu = '0'";
        //echo $requete;

        $resultat = requete_sql($id, $requete, 1);

        if($resultat['nbr'] > 0){
            $_SESSION['location'] = 1;
        }
        else{
            $_SESSION['location'] = 0;
        }
    }
    else
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
}

/*
Fonction

*/
function dateDifference($date_1 , $date_2 )
{
    $diff = abs($date_1 - $date_2);

    return $diff;
}

/*
Fonction

*/
function nb_trottinette_louer($id){
    $requete = "SELECT COUNT(id_trottinette) AS cnt FROM trottinette WHERE en_panne = '0' AND a_quai = '1'";
    $resultat = requete_sql($id, $requete, 1);

    return $resultat['cnt'];
}

/*
Fonction

*/
function nb_trottinette_en_panne($id){
    $requete = "SELECT COUNT(id_trottinette) AS cnt FROM trottinette WHERE en_panne = '1' ";
    $resultat = requete_sql($id, $requete, 1);

    return $resultat['cnt'];
}

/*
Fonction

*/
function nb_probleme_regler($id){
    $requete = "SELECT COUNT(id_erreur) AS cnt FROM erreur WHERE regler = '1' ";
    $resultat = requete_sql($id, $requete, 1);

    return $resultat['cnt'];
}

/*
Fonction

*/
function nb_probleme_non_regler($id){
    $requete = "SELECT COUNT(id_erreur) AS cnt FROM erreur WHERE regler = '0' ";
    $resultat = requete_sql($id, $requete, 1);

    return $resultat['cnt'];
}

?>