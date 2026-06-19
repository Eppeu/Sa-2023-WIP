<!-- 
utilisateur_is_admin.php - Statut d'un utilisateur
Cette page permet de vérifier le statut d'un utilisateur afin de pouvoir lui attribuer 
le rôle d'admin directement depuis le site.
-->

<?php
session_start();

require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();

// Si le client n'est pas connecté et n'est pas un utilisateur admin, il est redirigé à la page d'accueil
if(!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != TRUE) header("Location: ../public/index.php");

// Récupère les informations de l'utilisateur s'il est connecté
if(isset($_SESSION['email'])){
    $utilisateur_infos_requete = $bdd->prepare("SELECT * FROM utilisateur WHERE email=?");
    $utilisateur_infos_requete->execute(array($_SESSION['email']));
    $utilisateur_infos = $utilisateur_infos_requete->fetch();
}

// Vérifie que l'id a bien été récupéré
if(isset($_GET['id_utilisateur']) AND !empty($_GET['id_utilisateur'])){
    // Récupère l'id de l'élément voulu depuis l'URL
    $id_utilisateur_get = $_GET['id_utilisateur'];

    $utilisateur_infos_requete = $bdd->prepare("SELECT * FROM utilisateur WHERE id_utilisateur=?");
    $utilisateur_infos_requete->execute(array($id_utilisateur_get));
    $utilisateur_infos = $utilisateur_infos_requete->fetch();

    if($utilisateur_infos['is_admin']==1){
        $new_status = 0;

        $update_utilisateur_is_admin = $bdd->prepare("UPDATE utilisateur SET is_admin = ? WHERE id_utilisateur=?;");
        $update_utilisateur_is_admin->execute(Array($new_status, $id_utilisateur_get));
        header('Location: ../private/dashboard_admin.php');
        exit();
    }else{
        $new_status = 1;

        $update_utilisateur_is_admin = $bdd->prepare("UPDATE utilisateur SET is_admin = ? WHERE id_utilisateur=?;");
        $update_utilisateur_is_admin->execute(Array($new_status, $id_utilisateur_get));
        header('Location: ../private/dashboard_admin.php');
        exit();
    }
}

?>