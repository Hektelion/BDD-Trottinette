<?php
/*
config.php

 - 2 Constantes
    ROOTPATH : correspond à la racine du site
    TITRESITE : stock le titre du site

 - 1 Variable global
    queries : nombre de requête effectué

*/

define('ROOTPATH', 'http://'.$_SERVER['HTTP_HOST'], true);
define('TITRESITE', 'Location Trottinette', true);
$queries = 0;
?>