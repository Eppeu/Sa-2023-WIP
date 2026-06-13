<?php
session_start();
unset($_SESSION["nom_utilisateur"]);
// session_abort();
session_destroy();

header('Location: ../public/index.php');
exit;
?>