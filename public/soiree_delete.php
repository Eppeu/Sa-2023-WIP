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

if(isset($_GET['id_soiree']) AND !empty($_GET['id_soiree'])){
    // Récupère l'id de l'élément voulu depuis l'URL
    $id_soiree_get = $_GET['id_soiree'];
    $soiree_date_limite_requete = $bdd->prepare("SELECT id_soiree FROM soiree 
    WHERE id_soiree=?;");
    $soiree_date_limite_requete->execute(array($id_soiree_get));

    $delete_vote = $bdd->prepare('DELETE FROM vote WHERE id_soiree = ?;');
    $delete_vote->execute(array($id_soiree_get));

    $delete_soiree_db = $bdd->prepare('DELETE FROM soiree WHERE id_soiree = ?;');
    $delete_soiree_db->execute(array($id_soiree_get));
    header("Location: ./soirees");
}



?>