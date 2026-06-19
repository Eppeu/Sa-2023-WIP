<!-- 
deconnexion.php - Deconnexion
Cette page permet de finir une session et de déconnecter un utilisateur.
-->

<?php
session_start();
unset($_SESSION["email"]);
// session_abort();
session_destroy();

header('Location: ../public/index.php');
exit;
?>