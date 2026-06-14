<?php
session_start();
unset($_SESSION["email"]);
// session_abort();
session_destroy();

header('Location: ../public/index.php');
exit;
?>