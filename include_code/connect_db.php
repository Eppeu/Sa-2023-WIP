<!-- 
connect.php - Connexion à la BDD
Cette page comme footer.php sert a être réutilliser afin de gagner un peu de place et de 
visibilité dans le code.
-->

<?php
session_start();

require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();

// Récupère les informations de l'utilisateur s'il est connecté
if(isset($_SESSION['email'])){
    $utilisateur_infos_requete = $bdd->prepare("SELECT * FROM utilisateur WHERE email=?");
    $utilisateur_infos_requete->execute(array($_SESSION['email']));
    $utilisateur_infos = $utilisateur_infos_requete->fetch();
}
?>