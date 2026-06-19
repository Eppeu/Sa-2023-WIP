<!-- 
soiree_delete.php - Supprimer une soirée
Cette page est un intermédiare de page différentes afin de pouvoir supprimer une soirée.
Uniquement l'admin et la personne ayant crée une soirée peuvent y accéder.
-->


<?php
include('../include_code/connect_db.php');

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

    // Si c'est un admin qui a supprimé la soirée depuis le dashboard, il y est ramené. Si c'est un utilisateur classique, il est renvoyé aux soirées.
    if(!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != TRUE){
        header("Location: ../private/dashboard_admin");
        exit();
    }else{
        header("Location: ../public/soirees");
        exit();
    }
}
?>