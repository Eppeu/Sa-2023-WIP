<!-- 
film_delete.php - Supprimer un film
Cette page permet à un admin de supprimer un film depuis le dashboard
de PopCo.
-->

<?php
require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();

if(isset($_GET['id_film']) AND !empty($_GET['id_film'])){
    // Récupère l'id de l'élément voulu depuis l'URL
    $id_film_get = $_GET['id_film'];

    // Supprime le film à l'id voulu
    $delete_movie_db = $bdd->prepare('DELETE FROM film WHERE id_film = ?');
    $delete_movie_db->execute(array($id_film_get));

    header("Location: ../private/dashboard_admin");
    exit();
}
else{
    echo "Une erreur s'est produite, l'identifiant n'est pas parvenu à être récupéré, veuillez revenir à la page précédente.";
}
?>