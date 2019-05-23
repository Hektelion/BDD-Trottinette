<?php
/*
colg.php

Colonne gauche regroupe differente stats sur l'activité du site

*/
?>

<div><?php echo "Il y'a " . nb_trottinette_louer($id) . " trottinette à louer"; ?></div>
<div><?php echo "Il y'a " . nb_trottinette_en_panne($id) . " trottinette en panne"; ?></div>
<div><?php echo "Il y'a " . nb_probleme_regler($id) . " probleme regler"; ?></div>
<div><?php echo "Il y'a " . nb_probleme_non_regler($id) . " probleme non regler"; ?></div>