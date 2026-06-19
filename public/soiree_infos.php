<!-- 
soiree_info.php - Information d'une soirée.
Cette page permet de voir les différentes informations d'une soirée et permet l'inscription d'une soirée à cette dernière.
-->


<?php
include('../include_code/connect_db.php');

// Vérifie que l'id a bien été récupéré
if(isset($_GET['id_soiree']) AND !empty($_GET['id_soiree'])){
    // Récupère l'id de l'élément voulu depuis l'URL
    $id_soiree_get = $_GET['id_soiree'];

    // Récupère la soirée où la date de limite de vote est atteinte
    $soiree_date_limite_requete = $bdd->prepare("SELECT id_soiree FROM soiree 
    WHERE date_limite_vote < NOW() AND id_soiree=?;");
    $soiree_date_limite_requete->execute(array($id_soiree_get));

    if($soiree_date_limite = $soiree_date_limite_requete->fetch()){
        $is_limit_reached = true;
    }else{
        $is_limit_reached = false;
    }

    // Récupération des informations de la soirée, des films et des lieux et l'id de l'auteur de la soirée.
    $soiree_infos_requete = $bdd->prepare('SELECT s.*,
    s.id_utilisateur AS id_user_movie,
    f1.id_film AS film1_id_film, f1.nom_film AS film1_nom_film, f1.affiche AS film1_affiche,
    f2.id_film AS film2_id_film, f2.nom_film AS film2_nom_film, f2.affiche AS film2_affiche,
    f3.id_film AS film3_id_film, f3.nom_film AS film3_nom_film, f3.affiche AS film3_affiche,
    f4.id_film AS film4_id_film, f4.nom_film AS film4_nom_film, f4.affiche AS film4_affiche,
    f5.id_film AS film5_id_film, f5.nom_film AS film5_nom_film, f5.affiche AS film5_affiche,
    l1.id_lieu AS lieu1_id, l1.adresse AS lieu1_adresse,
    l2.id_lieu AS lieu2_id, l2.adresse AS lieu2_adresse,
    l3.id_lieu AS lieu3_id, l3.adresse AS lieu3_adresse,
    s.choix_1_lieu AS choix_1_lieu,
    s.choix_2_lieu AS choix_2_lieu,
    s.choix_3_lieu AS choix_3_lieu

    FROM soiree s
    JOIN film f1 ON s.choix_1_film = f1.id_film
    JOIN film f2 ON s.choix_2_film = f2.id_film
    JOIN film f3 ON s.choix_3_film = f3.id_film
    JOIN film f4 ON s.choix_4_film = f4.id_film
    JOIN film f5 ON s.choix_5_film = f5.id_film
    LEFT JOIN lieu l1 ON s.choix_1_lieu = l1.id_lieu
    LEFT JOIN lieu l2 ON s.choix_2_lieu = l2.id_lieu
    LEFT JOIN lieu l3 ON s.choix_3_lieu = l3.id_lieu

    WHERE s.id_soiree = ?;');

    $soiree_infos_requete->execute(array($id_soiree_get));

    // Si cette colonne existe, les données déjà éxistantes sont récupées
    if($soiree_infos_requete->rowCount() > 0){
        $soiree_infos = $soiree_infos_requete->fetch();
    }
    else{
        echo "soiree introuvable. Vous allez être redirigé vers les soirees.";
    }
    
    // Compte les votes par FILM de la soirée
    $count_votes_film_requete = $bdd->prepare('SELECT choix_film, COUNT(*) AS nb_votes_film
    FROM vote
    WHERE id_soiree = ?
    GROUP BY choix_film;');
    $count_votes_film_requete->execute([$id_soiree_get]);

    // Stocke les résultats dans un tableau indexé par id_film
    $votes_par_film = [];
    while ($row = $count_votes_film_requete->fetch()) {
        $votes_par_film[$row['choix_film']] = $row['nb_votes_film'];
    }


    // Comptage nombre lieu
    $count_votes_lieu_requete = $bdd->prepare('SELECT choix_lieu, COUNT(*) AS nb_votes_lieu
    FROM vote
    WHERE id_soiree = ?
    GROUP BY choix_lieu;');
    $count_votes_lieu_requete->execute([$id_soiree_get]);

    $votes_par_lieu = [];
    while ($row = $count_votes_lieu_requete->fetch()) {
        $votes_par_lieu[$row['choix_lieu']] = $row['nb_votes_lieu'];
    }

    // Comptage de personnes inscrites à la soirée (Une personne ayant voté est une personne inscrite)
    $get_count_sql = $bdd->prepare("SELECT * FROM vote JOIN soiree ON 
    vote.id_soiree = soiree.id_soiree WHERE soiree.id_soiree = ?;");
    $get_count_sql->execute(array($id_soiree_get));

    $get_count = $get_count_sql->rowCount();

    

    if(isset($_SESSION['email'])){
        // Récupère les informations de l'utilisateur connecté
        $utilisateur_infos_requete = $bdd->prepare("SELECT * FROM utilisateur WHERE email=?");
        $utilisateur_infos_requete->execute(array($_SESSION['email']));
        $utilisateur_infos = $utilisateur_infos_requete->fetch();

        // Récupère, si ça existe, le vote de l'utilisateur sur cette soirée
        $vote_exist_requete = $bdd->prepare('SELECT vote.*
        FROM vote
        JOIN utilisateur ON utilisateur.id_utilisateur = vote.id_utilisateur
        JOIN soiree ON soiree.id_soiree = vote.id_soiree
        WHERE utilisateur.id_utilisateur = ? AND soiree.id_soiree = ?;');

        $vote_exist_requete->execute(array($utilisateur_infos['id_utilisateur'], $id_soiree_get));
        $vote_exist = $vote_exist_requete->rowCount() > 0;
    }
} else{
    echo "Une erreur s'est produite, l'identifiant n'est pas parvenu à être récupéré, veuillez revenir à la page précédente.";
}


// Fonction qui permet de prendre en argument 4 strings afin de générer les cartes des différents films proposés
function generate_movie_vote($film_affiche,$film_choix,$film_id,$film_nom) {
    global $soiree_infos;
    global $votes_par_film;
    echo 
    '
    <div class="card p-0 flex-row overflow-hidden" style="height: 85px;">
        <img src="' . $soiree_infos[$film_affiche] . '"class="object-fit-cover" style="width: 100px; flex-shrink: 0;">
        <div class="card-body bg-ctm-primary-color-subtle p-2 d-flex flex-column justify-content-center">
            <h6 class="card-title mb-0">' . $soiree_infos[$film_nom] . '</h6>
            <p class="card-caption mb-0">Nombre de vote pour ce film : '; echo isset($votes_par_film[$soiree_infos[$film_choix]]) ? $votes_par_film[$soiree_infos[$film_choix]] : 0; echo '</p>';
            if(isset($soiree_infos["film_choisi"]) && $soiree_infos[$film_id] == $soiree_infos['film_choisi']){
                echo '<span class="badge text-bg-primary rounded-pill">Film choisi !</span>';
            } 
        echo '</div>
    </div>
    ';
}


// Même but que l'autre fonction mais pour les lieux d'une soirée.
function generate_lieu ($lieu_addresse,$lieu_id) {
    global $soiree_infos;
    global $votes_par_lieu;
    echo
    '
    <a class="btn btn-light btn-lg w-100" for="btn-lieu-1">'
        . $soiree_infos[$lieu_addresse] .
        '<p class="card-caption mb-0">Nombre de vote pour ce lieu : '; echo isset($votes_par_lieu[$soiree_infos[$lieu_id]]) ? $votes_par_lieu[$soiree_infos[$lieu_id]] : 0; echo '</p>';
        if(isset($soiree_infos['lieu_choisi']) && $soiree_infos[$lieu_id] == $soiree_infos['lieu_choisi']){
            echo '<span class="badge text-bg-primary rounded-pill">Lieu choisi !</span>';
        } 
    echo '</a>
    ';
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/icons/PopCo_favicon.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../styles/main.css">
    <!-- Font Awesome pour les icônes -->
        <script src="https://kit.fontawesome.com/4b69bc6b92.js" crossorigin="anonymous"></script>
    <!-- Bootstrap Icons  -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Informations de la soiree</title>

</head>
<body class="bg-ctm-terciary-color">
    <header> 
         <!-- Header contenant le menu de navigation version pour écran normal et version pour écran réduit -->
        <div class="container-fluid p-0">
                <nav id="header_popco" class="navbar navbar-expand bg-ctm-primary-color rounded-bottom-5 ">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="../public/index.php">
                            <img src="../assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
                            <!-- Insertion de l'icône du logo PopCo -->
                        </a>
                        <div class="collapse navbar-collapse justify-content-end justify-content-md-between">
                            <!-- navbar sous mode collapse avec justify content between -->
                            <ul class="navbar-nav mb-2 mb-lg-0 d-none d-md-flex">
                                 <!-- class de la barre de navigation (navbar) avec une marge de bas de 2 et de 0 à partir du breakpoint large -->
                                <li class="nav-item active">
                                    <!-- item de navigation actif -->
                                    <a class="nav-link" href="../public/index.php">Accueil</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="../public/soirees">Soirées</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="../public/films">Films</a>
                                    <!-- lien de navigation -->
                                </li>
                                <?php if(isset($_SESSION['email'])) { ?>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="../public/soiree_create.php">Créer une soirée</a>
                                    <!-- lien de navigation -->
                                </li> 
                                    <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']==TRUE) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link bootstrap_nav_item_color" href="../private/film_create">Ajouter un film</a>
                                        <!-- lien de navigation -->
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link bootstrap_nav_item_color" href="../private/dashboard_admin">Dashboard administrateur</a>
                                        <!-- lien de navigation -->
                                    </li>
                                    <?php } ?>
                                <?php } ?>

                            </ul>

                            <?php
                            if(isset($_SESSION['email'])) {
                                ?>
                                <div class="dropdown dropstart d-md-block d-none">
                                <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <h2><i class="bi bi-person fs-3 link-ctm-terciary-color-subtle me-4"></i></h2>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="mx-3"><?= $utilisateur_infos['nom_utilisateur'];?> <?= $utilisateur_infos['prenom_utilisateur'];?></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="../public/utilisateur">Votre profil</a></li>
                                    <li><a class="dropdown-item" href="../private/deconnexion">Se déconnecter</a></li>
                                </ul>
                                </div>
                                <?php
                            }else{
                                ?>
                                <ul class="navbar-nav mb-2 mb-lg-0 gap-2 me-0 d-none d-md-flex">
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red-subtle" href="../public/connexion">Se connecter</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red" href="../public/compte_create">Créer un compte</a>
                                    </li>
                                </ul>
                                <!-- Boutons Rouges (un de couleur légère et l'autre non) pour créer un compte et se connecter -->
                        </div>
                        <?php } ?>

                        <a class="fs-1 d-flex align-self-end d-md-none text-success" data-bs-toggle="offcanvas" href="#menu_phone" aria-controls="offcanvasExample">
                            <i class="bi bi-list link-ctm-terciary-color"></i>
                        </a>
                        <div class="offcanvas-md d-md-none offcanvas-end bg-ctm-terciary-color" tabindex="-1" id="menu_phone" aria-labelledby="menu_phoneLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="menu_phoneLabel">PopCo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#menu_phone" aria-label="Close"></button>
                                <!-- ajout de la class offcanvas pour créer le menu burger (sur la version réduite du site) -->
                            </div>
                            <div class="offcanvas-body d-flex flex-column justify-content-between px-0">
                                <ul class="list-group">
                                    <a href="./index" class="list-group-item list-group-item-action active list-group-item-ctm-terciary-color-subtle" aria-current="true">
                                        Accueil
                                    </a>
                                    <a href="./soirees" class="list-group-item list-group-item-action">
                                        Soirées
                                    </a>
                                    <a href="./films.php" class="list-group-item list-group-item-action">
                                        Films
                                    </a>
                                    <?php if(isset($_SESSION['email'])) { ?>
                                    <a href="../public/soiree_create" class="list-group-item list-group-item-action">
                                        Créer une soirée
                                    </a>
                                    <?php } ?>
                                    <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']==TRUE) { ?>
                                        <a class="list-group-item list-group-item-action" href="../private/film_create">
                                            Ajouter un film
                                        </a>
                                        <a class="list-group-item list-group-item-action" href="../private/dashboard_admin">
                                            Dashboard administrateur
                                        </a>
                                    <?php } ?>
                                </ul>

                                <?php
                                if(isset($_SESSION['email'])) {
                                    ?>
                                    <div class="dropup-center dropup">
                                        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <h2><i class="bi bi-person fs-2 mx-2 link-ctm-terciary-color-subtle me-4 text-decoration-none"><?= $utilisateur_infos['nom_utilisateur'];?> <?= $utilisateur_infos['prenom_utilisateur'];?></i></h2>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="../public/utilisateur">Votre profil</a></li>
                                            <li><a class="dropdown-item" href="../private/deconnexion">Se déconnecter</a></li>
                                        </ul>
                                    </div>
                                    <?php
                                }else{
                                    ?>
                                    <div class="container-fluid d-md-flex justify-content-end gap-2">
                                        <a class="btn btn-ctm-red-subtle" href="../public/connexion.php">Se connecter</a>
                                        <a class="btn btn-ctm-red" href="../public/compte_create.php">Créer un compte</a>
                                        <!-- Bouton rouge pour se connecter / créer un compte -->
                                    </div>                            
                                <?php } ?>

                            </div>
                        </div>

                    </div>
                </nav>
        </div>
    </header>

    <main>
        <div class="container flex-column my-5">
            <div class="row flex-sm-column flex-lg-row">
                
                <div class="col-12 col-sm-2">
                    <img src="<?= $soiree_infos['image_soiree'] ?>" class="img-resize-choose rounded-2 object-fit-cover" alt="...">
                </div>
                
                <div class="align-self-start col-12 col-sm-4 col-lg-3 offset-lg-1">
                    <h1 id="nom_soiree"><?= $soiree_infos['nom_soiree'] ?></h1>
                    <p><?= $soiree_infos['description_soiree'] ?></p>
                    <p>Genre : <?= $soiree_infos['genre_soiree'] ?></p>
                </div>
                
                <div class="align-self-start col-12 col-sm-4 col-lg-3">
                    <h3 id="genre_soiree">Nombre d'invités maximum : <?= $soiree_infos['nb_personne_max'] ?> personnes</h3>
                    <p>Date de début : <?= $soiree_infos['date_debut'] ?></p>
                    <p>Date de fin prévue : <?= $soiree_infos['date_fin'] ?></p>
                    <p>Date de vote limite prévue : <?= $soiree_infos['date_limite_vote'] ?></p>
                </div>
        
                <!-- Execution des fonctions créées précédements -->
                <div class="col-12 col-lg-5 col-sm-6 mt-4 d-flex flex-column gap-2">
                    <?php generate_movie_vote("film1_affiche","choix_1_film","film1_id_film","film1_nom_film"); ?>
        
                    <?php generate_movie_vote("film2_affiche","choix_2_film","film2_id_film","film2_nom_film"); ?>
        
                    <?php generate_movie_vote("film3_affiche","choix_3_film","film3_id_film","film3_nom_film"); ?>

                    <?php generate_movie_vote("film4_affiche","choix_4_film","film4_id_film","film4_nom_film"); ?>

                    <?php generate_movie_vote("film5_affiche","choix_5_film","film5_id_film","film5_nom_film"); ?>
        
                </div>
        
                <div class="col-12 col-md-5 offset-md-1 d-flex flex-column justify-content-center gap-3 mt-4">
                    <?php generate_lieu("lieu1_adresse","lieu1_id") ?>

                    <?php generate_lieu("lieu2_adresse","lieu2_id") ?>

                    <?php generate_lieu("lieu3_adresse","lieu3_id") ?>
                </div>
                
            </div>
        </div>

        <div class="ms-5 col-10">
            <?php if(($get_count == $soiree_infos["nb_personne_max"] )){ // Si existe déjà vote pour id connecté et soirée sélectionnée : bouton disabled ?>
                <a disabled href="./vote.php?id_soiree=<?php echo $soiree_infos['id_soiree']; ?>" class="btn btn-ctm-red py-3 w-100 rounded-1 disabled">Il n'y a plus de places disponibles pour cette soirée !</a>
            <?php
            }else if(isset($vote_exist) AND $vote_exist == TRUE){ ?>
                <a disabled href="./vote.php?id_soiree=<?php echo $soiree_infos['id_soiree']; ?>" class="btn btn-ctm-red py-3 w-100 rounded-1 disabled">Vous avez déjà voté !</a>
            <?php } else{ ?>
                <a href="./vote.php?id_soiree=<?php echo $soiree_infos['id_soiree']; ?>" class="btn btn-ctm-red py-3 w-100 rounded-1">Voter et s'inscrire !</a>
            <?php } ?>
        </div>
        <div class ="mt-4 mb-5 mx-5 d-flex gap-3">
            <?php if(isset($utilisateur_infos['id_utilisateur']) && ($utilisateur_infos['id_utilisateur']) == $soiree_infos['id_user_movie'] ){ // Check si la soirée a été créée par l'utilateur qui consulte la page
            ?>
            <form method='POST' action="./soiree_update">
                <button name="update_party" type="submit" class="btn btn-primary">Modifier la soirée</button>

                <input hidden name='SoireeID' id='name_s' value="<?= $soiree_infos['id_soiree'] ?>">
                <input hidden name='NameSoiree' id='name_s' value="<?= $soiree_infos['nom_soiree'] ?>">
                <input hidden name='DescriptionSoiree' id='desc_s' value="<?=$soiree_infos['description_soiree'] ?>">
                <input hidden name='GenreSoiree' id='genre_s' value="<?= $soiree_infos['genre_soiree']; ?>">
                <input hidden name='NumberSoiree' id='nmb_s' value="<?= $soiree_infos['nb_personne_max']; ?>">
                <input hidden name='DateStartSoiree' id='DateS_s' value="<?= $soiree_infos['date_debut']; ?>">
                <input hidden name='DateEndSoiree' id='DateE_s' value="<?= $soiree_infos['date_fin']; ?>">
                <input hidden name='DateLimiteVoteSoiree' id='DateVL_s' value="<?= $soiree_infos['date_limite_vote']; ?>">
                <input hidden name='ChoixLieu1' id='CL1_s' value="<?= $soiree_infos['lieu1_adresse']; ?>">
                <input hidden name='ChoixLieu2' id='CL2_s' value="<?= $soiree_infos['lieu2_adresse']; ?>">
                <input hidden name='ChoixLieu3' id='CL3_s' value="<?= $soiree_infos['lieu3_adresse']; ?>">
                <input hidden name='ImageSoire' id='img_s' value="<?= $soiree_infos['image_soiree']; ?>">
            </form>

            <a class="btn btn-ctm-red-subtle col-4" href="../public/soiree_delete.php?id_soiree=<?= $soiree_infos['id_soiree'] ?>">Supprimer la soirée</a>
            <?php } ?>

            <?php if(isset($utilisateur_infos['id_utilisateur']) && $utilisateur_infos['id_utilisateur'] == $soiree_infos['id_user_movie'] ){ // Check si la soirée a été créée par l'utilateur qui consulte la page
                if($is_limit_reached){ // Date de vote dépassée ?>
                    <a class="btn btn-ctm-red col-4" href="../private/decision_choix?id_soiree=<?= $soiree_infos['id_soiree'] ?>">Décider des résultats</a>
                <?php }else{ // Date de vote pas dépassée ?> 
                    <a class="btn btn-ctm-red disabled col-4" href="../private/decision_choix?id_soiree=<?= $soiree_infos['id_soiree'] ?>">Décider des résultats</a>
                <?php } ?>

            <?php } ?>
        </div>
    </main>

    <!-- Footer via un include afin de ne pas avoir de code répété  -->
    <footer id="footer_popco" class="container-fluid py-3 rounded-top-5 bg-ctm-primary-color">
        <?php include("../include_code/footer.php");?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>