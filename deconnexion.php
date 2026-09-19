<?php
session_start();      // on reprend la session en cours
session_unset();      // on vide toutes les données de la session
session_destroy();    // on détruit la session
header('Location: index.php'); // retour à l'accueil
exit;
?>