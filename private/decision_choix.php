<!-- 
decison_choix.php - Décision
Cette page permet de savoir quel film et lieu ont gagné avant une soirée.
Si il y a une égalité, le film sera décidé au hazard.
-->

<?php

require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();

// Récupère toutes les soirées dont la date limite est dépassée et pas encore traitées
$soirees_a_traiter = $bdd->query("
    SELECT id_soiree FROM soiree 
    WHERE date_limite_vote < NOW() 
    AND film_choisi IS NULL
");

// Fonction qui va calculer le lieu et film gagnant pour chaque soirée qui remplir la condition
foreach ($soirees_a_traiter as $soiree) {
    calculerResultat($soiree['id_soiree']);
}

// La fonction (oui)
function calculerResultat($id_soiree) {
    global $bdd; // global permet d'utiliser la fonction $bdd dans la fonciton, sinon elle est pas reconnue

    // Pour les FILMS
    // votes_films récupère pour chaque film son nombre de vote en groupant par film pour chaque résultat, du plus voté au moins voté
    $votes_films = $bdd->prepare('SELECT choix_film, COUNT(*) AS nb_votes FROM vote WHERE id_soiree = ? GROUP BY choix_film ORDER BY nb_votes DESC;');
    $votes_films->execute([$id_soiree]);
    // Récupère tous les résultats d'un coup et les mets dans un tableau
    $resultats_films = $votes_films->fetchAll();

    if (empty($resultats_films)) {
        return; // personne n'a voté, on ne fait rien
    }

    // Trouve le nombre max de votes en prenant celui qui arrive en première position vu que le tableau est créé en partant du film le plus voté
    $max_votes_film = $resultats_films[0]['nb_votes'];

    // Récupère tous les films qui ont le même nombre de votes que $max_votes_film pour avoir le tableau de ceux à égalité
    $films_a_egalite = array_filter($resultats_films, function($row) use ($max_votes_film) {
        return $row['nb_votes'] == $max_votes_film;
    });

    // // S'il y a plusieurs films à égalité...
    // if $films_a_egalite > 2{
    //     // Organiser 
    // }

    // Refait les index du tableau (si jamais y'a genre [0], [3], [5] lorsque les films sont triés du tableau du dessus)
    $films_a_egalite = array_values($films_a_egalite);

    // array_rand() choisit un index de tableau au hasard
    $film_gagnant = $films_a_egalite[array_rand($films_a_egalite)]['choix_film'];


    // La même chose pour les LIEU
    $votes_lieux = $bdd->prepare('
        SELECT choix_lieu, COUNT(*) AS nb_votes
        FROM vote
        WHERE id_soiree = ?
        GROUP BY choix_lieu
        ORDER BY nb_votes DESC
    ');
    $votes_lieux->execute([$id_soiree]);
    $resultats_lieux = $votes_lieux->fetchAll();

    if (empty($resultats_lieux)) {
        return;
    }

    $max_votes_lieu = $resultats_lieux[0]['nb_votes'];

    $lieux_a_egalite = array_filter($resultats_lieux, function($row) use ($max_votes_lieu) {
        return $row['nb_votes'] == $max_votes_lieu;
    });
    $lieux_a_egalite = array_values($lieux_a_egalite);

    $lieu_gagnant = $lieux_a_egalite[array_rand($lieux_a_egalite)]['choix_lieu'];


    // Ajout de film chosi et lieu choisi (jusque là null)
    $update = $bdd->prepare('
        UPDATE soiree 
        SET film_choisi = ?, lieu_choisi = ?
        WHERE id_soiree = ?
    ');
    $update->execute([$film_gagnant, $lieu_gagnant, $id_soiree]);

    header('Location: ../public/soirees.php');
    echo "test";
}
?>