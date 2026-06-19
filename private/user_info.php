<!-- 
user_info.php - Obtention IP
Cette page permet d'obtenir l'IP de l'utilisateur afin de la garder et de créer une variable plus tard 
qui permettra de bloquer la connexion si l'utilisateur a déjà un compte crée.
-->

<?php 
$user_ip = $_SERVER['REMOTE_ADDR'];
if (!isset($_COOKIE[$user_ip])) {
    setcookie($user_ip, "false", time() + (86400 * 30), "/");
}
?>