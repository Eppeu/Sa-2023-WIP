<?php
30 * * * * home/path/to/command/the_command.sh

$soirees_a_traiter = $bdd->query("
    SELECT id_soiree FROM soiree 
    WHERE date_limite_vote < NOW() 
    AND film_choisi IS NULL
");

foreach ($soirees_a_traiter as $soiree) {
    calculerResultat($soiree['id_soiree']);
}

function calculerResultat($tableau){
    if ($tableau[0] == $tableau[1]){
        // Compte les votes par film de la soirée
        $count_votes_requete = $bdd->prepare('
        SELECT choix_film, COUNT(*) AS nb_votes
        FROM vote
        WHERE id_soiree = ?
        GROUP BY choix_film
        ');
        $count_votes_requete->execute([$id_soiree_get]);
    }
}

// récupération informations soiree à partir de l'id


// Vérification date limite de vote atteint

// Si atteint :
// Compter pour chaque film le nombre de votes
// Compter qui a le plus de vote


// Si un ou plusieurs films ont le même nombre de vote
// Faire un choix aléatoire entre les films qui ont le plus de vote
// Retenir le film qui gagne dans soiree.lieu_choisi
// Sinon


// Si un ou plusieurs lieu ont le même nombre de vote
// Faire un choix aléatoire entre les lieux qui ont le plus de vote
// Retenir le lieu qui gagne dans soiree.lieu_choisi
// Sinon

?>