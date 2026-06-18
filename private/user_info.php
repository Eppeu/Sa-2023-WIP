<?php 
$user_ip = $_SERVER['REMOTE_ADDR'];
if (!isset($_COOKIE[$user_ip])) {
    setcookie($user_ip, "false", time() + (86400 * 30), "/");
}
?>