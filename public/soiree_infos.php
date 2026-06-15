<?php
session_start();

require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();

// Vérifie que l'id a bien été récupéré
if(isset($_GET['id_soiree']) AND !empty($_GET['id_soiree'])){
    // Récupère l'id de l'élément voulu depuis l'URL
    $id_soiree_get = $_GET['id_soiree'];

    // Récupère la soirée à l'id voulu
    $soiree_infos_requete = $bdd->prepare('SELECT
    s.*,
    f1.nom_film AS film1_nom_film,
    f1.affiche AS film1_affiche,
    f2.nom_film AS film2_nom_film,
    f2.affiche AS film2_affiche,
    f3.nom_film AS film3_nom_film,
    f3.affiche AS film3_affiche,
    f4.nom_film AS film4_nom_film,
    f4.affiche AS film4_affiche,
    f5.nom_film AS film5_nom_film,
    f5.affiche AS film5_affiche
    
    FROM soiree s

    JOIN film f1 ON s.choix_1_film = f1.id_film
    JOIN film f2 ON s.choix_2_film = f2.id_film
    JOIN film f3 ON s.choix_3_film = f3.id_film
    JOIN film f4 ON s.choix_4_film = f4.id_film
    JOIN film f5 ON s.choix_5_film = f5.id_film

    WHERE s.id_soiree = ?;');

    $soiree_infos_requete->execute(array($id_soiree_get));

    // Comptage du nombre de soirées
    
    // Compte les votes par film de la soirée
    $count_votes_requete = $bdd->prepare('
    SELECT choix_film, COUNT(*) AS nb_votes
    FROM vote
    WHERE id_soiree = ?
    GROUP BY choix_film
    ');
    $count_votes_requete->execute([$id_soiree_get]);

    // Stocke les résultats dans un tableau indexé par id_film
    $votes_par_film = [];
    while ($row = $count_votes_requete->fetch()) {
        $votes_par_film[$row['choix_film']] = $row['nb_votes'];
    }
    

    // Si cette colonne existe, les données déjà éxistantes sont récupées
    if($soiree_infos_requete->rowCount() > 0){

        $soiree_infos = $soiree_infos_requete->fetch();
    }
    else{
        echo "soiree introuvable. Vous allez être redirigé vers les soirees.";
    }


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
}
else{
    echo "Une erreur s'est produite, l'identifiant n'est pas parvenu à être récupéré, veuillez revenir à la page précédente.";
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
                        <a class="navbar-brand" href="./index.php">
                            <img src="../assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
                            <!-- Insertion de l'icône du logo PopCo -->
                        </a>
                        <div class="collapse navbar-collapse justify-content-between">
                            <!-- navbar sous mode collapse avec justify content between -->
                            <ul class="navbar-nav mb-2 mb-lg-0 d-none d-md-flex">
                                 <!-- class de la barre de navigation (navbar) avec une marge de bas de 2 et de 0 à partir du breakpoint large -->
                                <li class="nav-item active">
                                    <!-- item de navigation actif -->
                                    <a class="nav-link" href="./index.php">Accueil</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./soirees.php">Soirées</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./films.php">Films</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./soiree_create.php">Créer une soirée</a>
                                    <!-- lien de navigation -->
                                </li>
                                
                                <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']==TRUE) { ?>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./new_film.php">Ajouter un film</a>
                                    <!-- lien de navigation -->
                                </li>
                                <?php } ?>
                            </ul>

                            <?php
                            if(isset($_SESSION['email'])) {
                                ?>
                                <ul class="navbar-nav mb-2 mb-lg-0 gap-2 me-0 d-none d-md-flex">
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red-subtle" href="./utilisateur.php">Votre profil</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red" href="../private/deconnexion.php">Se déconnecter</a>
                                    </li>
                                    <!-- Boutons Rouges (un de couleur légère et l'autre non) pour créer un compte et se connecter -->
                                </ul>
                                <?php
                            }else{
                                ?>
                                <ul class="navbar-nav mb-2 mb-lg-0 gap-2 me-0 d-none d-md-flex">
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red-subtle" href="./connexion.php">Se connecter</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red" href="./compte_create.php">Créer un compte</a>
                                    </li>
                                </ul>
                                <!-- Boutons Rouges (un de couleur légère et l'autre non) pour créer un compte et se connecter -->
                                
                        </div>
                        <?php } ?>

                        <a class="fs-1 d-block d-md-none text-success" data-bs-toggle="offcanvas" href="#menu_phone" aria-controls="offcanvasExample">
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
                                    <a href="./index.php" class="list-group-item list-group-item-action active list-group-item-ctm-terciary-color-subtle" aria-current="true">
                                        Accueil
                                        <!-- list group actif -->
                                    </a>
                                    <a href="./soirees.php" class="list-group-item list-group-item-action">
                                        Les soirées
                                        <!-- list group actif -->
                                    </a>
                                    <a href="./soiree_create.php" class="list-group-item list-group-item-action">
                                        Créer une soirée
                                        <!-- list group actif -->
                                    </a>
                                    <a href="./films.php" class="list-group-item list-group-item-action">
                                        Films proposés
                                        <!-- list group actif -->
                                    </a>
                                    <a href="./utilisateur.php" class="list-group-item list-group-item-action">
                                        Utilisateur
                                        <!-- list group actif -->
                                    </a>
                                </ul>

                                <div class="container-fluid d-md-flex justify-content-end gap-2">
                                    <a class="btn btn-ctm-red-subtle" href="./connexion.php">Se connecter</a>
                                    <a class="btn btn-ctm-red" href="./compte_create.php">Créer un compte</a>
                                    <!-- Bouton rouge pour se connecter / créer un compte -->
                                </div>
                            </div>
                        </div>

                    </div>
                </nav>
        </div>
    </header>

    <main>
        <div class="container-fluid flex-column mx-4 my-5 ">
            <div class="row flex-sm-column flex-lg-row">
                <div class="col-3 col-sm-2">
                    <img src="<?= $soiree_infos['image_soiree'] ?>" class="img-resize-choose rounded-2 object-fit-cover" alt="...">
                </div>
                <div class="align-self-start col-3 col-sm-4 col-lg-3 offset-lg-1">
                    <h1 id="nom_soiree"><?= $soiree_infos['nom_soiree'] ?></h1>
                    <p><?= $soiree_infos['description_soiree'] ?></p>
                    <p>Genre : <?= $soiree_infos['genre_soiree'] ?></p>
                </div>
                <div class="align-self-start col-3 col-sm-4 col-lg-3">
                    <h3 id="genre_soiree">Nombre d'invités maximum : <?= $soiree_infos['nb_personne_max'] ?> personnes</h3>
                    <p>Date de début : <?= $soiree_infos['date_debut'] ?></p>
                    <p>Date de fin prévue : <?= $soiree_infos['date_fin'] ?></p>
                    <p>Date de début : <?= $soiree_infos['date_debut'] ?></p>
                </div>
                <div class="row col-4 col-lg-12 col-sm-12 ms-0 mt-4 d-flex flex-column gap-2">
                    <div class="card p-0 flex-row overflow-hidden" style="height: 85px;">
                        <img src="<?= $soiree_infos['film1_affiche'] ?>" class="object-fit-cover" style="width: 100px; flex-shrink: 0;">
                        <div class="card-body bg-ctm-primary-color-subtle p-2 d-flex flex-column justify-content-center">
                            <h6 class="card-title mb-0"><?= $soiree_infos['film1_nom_film'] ?></h6>
                            <p class="card-caption mb-0">Nombre de vote pour ce film : <?= isset($votes_par_film[$soiree_infos['choix_1_film']]) ? $votes_par_film[$soiree_infos['choix_1_film']] : 0 ?></p>
                        </div>
                    </div>

                    <div class="card p-0 flex-row overflow-hidden" style="height: 85px;">
                        <img src="<?= $soiree_infos['film2_affiche'] ?>" class="object-fit-cover" style="width: 100px; flex-shrink: 0;">
                        <div class="card-body bg-ctm-primary-color-subtle p-2 d-flex flex-column justify-content-center">
                            <h6 class="card-title mb-0"><?= $soiree_infos['film2_nom_film'] ?></h6>
                            <p class="card-caption mb-0">Nombre de vote pour ce film : <?= isset($votes_par_film[$soiree_infos['choix_2_film']]) ? $votes_par_film[$soiree_infos['choix_2_film']] : 0 ?></p>
                        </div>
                    </div>

                    <div class="card p-0 flex-row overflow-hidden" style="height: 85px;">
                        <img src="<?= $soiree_infos['film3_affiche'] ?>" class="object-fit-cover" style="width: 100px; flex-shrink: 0;">
                        <div class="card-body bg-ctm-primary-color-subtle p-2 d-flex flex-column justify-content-center">
                            <h6 class="card-title mb-0"><?= $soiree_infos['film3_nom_film'] ?></h6>
                            <p class="card-caption mb-0">Nombre de vote pour ce film : <?= isset($votes_par_film[$soiree_infos['choix_3_film']]) ? $votes_par_film[$soiree_infos['choix_3_film']] : 0 ?></p>
                        </div>
                    </div>

                    <div class="card p-0 flex-row overflow-hidden" style="height: 85px;">
                        <img src="<?= $soiree_infos['film4_affiche'] ?>" class="object-fit-cover" style="width: 100px; flex-shrink: 0;">
                        <div class="card-body bg-ctm-primary-color-subtle p-2 d-flex flex-column justify-content-center">
                            <h6 class="card-title mb-0"><?= $soiree_infos['film4_nom_film'] ?></h6>
                            <p class="card-caption mb-0">Nombre de vote pour ce film : <?= isset($votes_par_film[$soiree_infos['choix_4_film']]) ? $votes_par_film[$soiree_infos['choix_4_film']] : 0 ?></p>
                        </div>
                    </div>

                    <div class="card p-0 flex-row overflow-hidden" style="height: 85px;">
                        <img src="<?= $soiree_infos['film5_affiche'] ?>" class="object-fit-cover" style="width: 100px; flex-shrink: 0;">
                        <div class="card-body bg-ctm-primary-color-subtle p-2 d-flex flex-column justify-content-center">
                            <h6 class="card-title mb-0"><?= $soiree_infos['film5_nom_film'] ?></h6>
                            <p class="card-caption mb-0">Nombre de vote pour ce film : <?= isset($votes_par_film[$soiree_infos['choix_5_film']]) ? $votes_par_film[$soiree_infos['choix_5_film']] : 0 ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ms-5 mb-5 col-9">
            <?php if(isset($vote_exist) AND $vote_exist == TRUE){ // Si existe déjà vote pour id connecté et soirée sélectionnée : bouton disabled
            ?>
                <a disabled href="./vote.php?id_soiree=<?php echo $soiree_infos['id_soiree']; ?>" class="btn btn-ctm-red py-3 w-100 rounded-1 disabled">Vous avez déjà voté !</a>
            <?php
            }else{
                ?>
                <a href="./vote.php?id_soiree=<?php echo $soiree_infos['id_soiree']; ?>" class="btn btn-ctm-red py-3 w-100 rounded-1">Voter !</a>
            <?php } ?>
        </div>
    </main>

    <footer id="footer_popco" class="container-fluid py-3 rounded-top-5 bg-ctm-primary-color">
        <div class="row g-1 d-flex align-items-center">
            <div class="col-4 fs-2 ps-4">
                <a href="" target="_blank" class="text-decoration-none link-ctm-terciary-color-subtle">
                    <i class="fab fa-instagram bootstrap_nav_item_color"></i>
                </a>
                <a href="" target="_blank" class="text-decoration-none link-ctm-terciary-color-subtle">
                    <i class="fab fa-facebook bootstrap_nav_item_color"></i>
                </a>
                <a href="" target="_blank" class="text-decoration-none link-ctm-terciary-color-subtle">
                    <i class="fab fa-discord bootstrap_nav_item_color"></i>
                </a>
                
            </div>
            <!-- icone lien vers les réseaux sociaux -->
            <div class="col-4 text-center">
                <img src="../assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
                <!-- Insertion de l'icône du logo PopCo -->
            </div>
            <!-- logo bas de page ramenant a la page d'accueil -->
            <div class="col-4 py-3 text-start d-lg-block text-end pe-4">
                <a class="text-decoration-none link-ctm-terciary-color-subtle" data-bs-toggle="modal" href="#popco_ml" role="button">
                Mentions légales
                </a>
                <!-- bouton pop up mentions légales -->
                <div class="modal fade" id="popco_ml" tabindex="-1" aria-labelledby="popco_mlLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content bg-ctm-terciary-color">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="popco_mlLabel">MENTIONS LÉGALES</h1>
                            <button type="button" class="btn-close link-ctm-primary-color-subtle" data-bs-dismiss="modal" aria-label="Close"></button>
                            <!-- bouton pour fermer les mentions légales (en forme de X)-->
                        </div>
                        <!-- mise en forme des mentions légales -->
                        <div class="modal-body text-center lh-sm">
                            <p>
                                Conformément aux dispositions de la loi n° 2004-575 du 21 juin 2004 pour la confiance en l'économie numérique, il est précisé aux utilisateurs du site PopCo l'identité des différents intervenants dans le cadre de sa réalisation et de son suivi.
                            </p>
                            <h5>Edition du site</h5>
                            <p>
                                Le présent site, accessible à l’URL https://PopCo.fr (le « Site »), est édité par :<br>
                                Astrid CALAIS, résidant Tarbes 65000, de nationalité Française (France), né(e) le 20/10/2003,
                            </p>
                            <h5>Hébergement</h5>
                            <p>
                                Le Site est hébergé par la société IUT de Tarbes, situé 1 Rue Lautréamont, 65000 Tarbes, (contact téléphonique ou email : +33562444200).
                            </p>
                            <h5>Directeur de publication</h5>
                            <p>
                                Le Directeur de la publication du Site est Astrid CALAIS.
                            </p>
                            <h5>Nous contacter</h5>
                            <p>
                                Par téléphone : +33739393939<br>
                                Par email : astrid.migu@cfm.fr<br>
                                Par courrier : Tarbes 65000<br><br>
                                Génération des mentions légales par Legalstart.
                            </p>
                        </div>
                        <!-- contenus des mentions légales -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-ctm-secondary-color-subtle" data-bs-dismiss="modal">Close</button>
                        </div>
                        <!-- bouton de fermeture des mentions légales -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>