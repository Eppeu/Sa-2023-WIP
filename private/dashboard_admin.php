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

$all_films = $bdd->query('SELECT *, LEFT(synopsis, 50) FROM film');
$all_soirees = $bdd->prepare("SELECT 
s.*, 
LEFT(description_soiree, 50),
s.id_utilisateur AS id_user_movie,
createur.email AS email_createur,
COALESCE(fchoisi.nom_film, 'Non choisi') AS film_choisi_ou_non_nom,
COALESCE(lchoisi.adresse, 'Non choisi') AS lieu_choisi_ou_non_nom

FROM soiree s
LEFT JOIN film fchoisi ON s.film_choisi = fchoisi.id_film
LEFT JOIN lieu lchoisi ON s.lieu_choisi = lchoisi.id_lieu
LEFT JOIN utilisateur createur ON s.id_utilisateur = createur.id_utilisateur;");
$all_soirees->execute();

$all_utilisateurs = $bdd->query("SELECT 
*, COALESCE(
      CASE WHEN 
         is_admin = 0
         THEN 'Non' 
         ELSE 'Oui'
         END, 
      is_admin) is_admin_display
FROM utilisateur");

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/icons/PopCo_favicon.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/style.css">
    <!-- Font Awesome pour les icônes -->
        <script src="https://kit.fontawesome.com/4b69bc6b92.js" crossorigin="anonymous"></script>
    <!-- Bootstrap Icons  -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Dashboard - PopCo</title>

</head>

<body  class="bg-ctm-terciary-color">
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
                                        <!-- list group actif -->
                                    </a>
                                    <a href="./soirees" class="list-group-item list-group-item-action">
                                        Les soirées
                                    </a>
                                    <a href="./films.php" class="list-group-item list-group-item-action">
                                        Films proposés
                                    </a>
                                    <?php if(isset($_SESSION['email'])) { ?>
                                    <a href="../public/soiree_create" class="list-group-item list-group-item-action">
                                        Film
                                    </a>
                                    <?php } ?>
                                    <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']==TRUE) { ?>
                                        <a class="list-group-item list-group-item-action" href="../private/film_create">
                                            Ajouter un film
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

    <main class="container-fluid px-0">
        <div class="bgImage3"></div>
        <div class="text-center py-5 callToAction">
            <h5 class="fs-1">Dashboard administrateur</h5>
            <p class="mt-5 fs-4">
                Retrouvez ci-dessous tous les films, les soirées et les utilisateurs de PopCo !
            </p>
        </div>

        <div class="mainPart p-5 d-flex flex-column gap-4">
            <div class="accordion mx-5" id="accordion_films">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tous_les_films" aria-expanded="false" aria-controls="element1">
                            <span class="fw-bold">Tous les films</span>
                        </button>
                    </h2>

                    <div id="tous_les_films" class="accordion-collapse collapse" data-bs-parent="#accordion1">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table py-5 table-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col">id</th>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Synopsis</th>
                                            <th scope="col">Genre</th>
                                            <th scope="col">Date de sortie</th>
                                            <th scope="col">Affiche</th>
                                            <th scope="col">Supprimer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($all_films_infos = $all_films->fetch()){ ?>
                                        <tr>
                                        <th scope="row"><?=$all_films_infos["id_film"]?></th>
                                        <td><?=$all_films_infos["nom_film"]?></td>
                                        <td><?=$all_films_infos["LEFT(synopsis, 50)"]?></td>
                                        <td><?=$all_films_infos["genre"]?></td>
                                        <td><?=$all_films_infos["date_sortie"]?></td>
                                        <td><?=$all_films_infos["affiche"]?></td>
                                        <td> <a target="_blank" class="btn btn-ctm-red" href="../private/film_delete?id_film=<?=$all_films_infos["id_film"]?>">Supprimer </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion mx-5" id="accordion_soirees">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#toutes_les_soirees" aria-expanded="false" aria-controls="element1">
                            <span class="fw-bold">Toutes les soirées</span>
                        </button>
                    </h2>

                    <div id="toutes_les_soirees" class="accordion-collapse collapse" data-bs-parent="#accordion1">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table py-5 table-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col">id</th>
                                            <th scope="col">Créateur</th>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Genre</th>
                                            <th scope="col">Nombre maximum d'invités</th>
                                            <th scope="col">Date de début</th>
                                            <th scope="col">Date de fin</th>
                                            <th scope="col">Date limite de vote</th>
                                            <th scope="col">Lieu choisi</th>
                                            <th scope="col">Film choisi</th>
                                            <th scope="col">Supprimer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($all_soirees_infos = $all_soirees->fetch()){ ?>
                                        <tr>
                                            <th scope="row"><?=$all_soirees_infos["id_soiree"]?></th>
                                            <td><?=$all_soirees_infos["email_createur"]?></td>
                                            <td><?=$all_soirees_infos["nom_soiree"]?></td>
                                            <td><?=$all_soirees_infos["LEFT(description_soiree, 50)"]?></td>
                                            <td><?=$all_soirees_infos["genre_soiree"]?></td>
                                            <td><?=$all_soirees_infos["nb_personne_max"]?></td>
                                            <td><?=$all_soirees_infos["date_debut"]?></td>
                                            <td><?=$all_soirees_infos["date_fin"]?></td>
                                            <td><?=$all_soirees_infos["date_limite_vote"]?></td>
                                            <td><?=$all_soirees_infos["film_choisi_ou_non_nom"]?></td>
                                            <td><?=$all_soirees_infos["lieu_choisi_ou_non_nom"]?></td>
                                            <td> <a target="_blank" class="btn btn-ctm-red" href="../private/soiree_delete?id_soiree=<?=$allSoirees_infos["id_soiree"]?>">Supprimer</td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion mx-5" id="accordion_utilisateurs">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tous_les_utilisateurs" aria-expanded="false" aria-controls="element1">
                            <span class="fw-bold bouton_close">Tous les utilisateurs</span>
                        </button>
                    </h2>

                    <div id="tous_les_utilisateurs" class="accordion-collapse collapse" data-bs-parent="#accordion1">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table py-5 table-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col">id</th>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Prénom</th>
                                            <th scope="col">E-mail</th>
                                            <th scope="col">Administrateur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($all_utilisateurs_infos = $all_utilisateurs->fetch()){ ?>
                                        <tr>
                                            <th scope="row"><?=$all_utilisateurs_infos["id_utilisateur"]?></th>
                                            <td><?=$all_utilisateurs_infos["nom_utilisateur"]?></td>
                                            <td><?=$all_utilisateurs_infos["prenom_utilisateur"]?></td>
                                            <td><?=$all_utilisateurs_infos["email"]?></td>
                                            <td><?=$all_utilisateurs_infos["is_admin_display"]?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <!-- Footer avec les liens vers instagram, discord, facebook, mentions légales -->
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
            <div class="col-4 text-center">
                <img src="../assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
                <!-- Insertion de l'icône du logo PopCo -->
            </div>
            <div class="col-4 py-3 text-start d-lg-block text-end pe-4">
                <a class="text-decoration-none link-ctm-terciary-color-subtle" data-bs-toggle="modal" href="#popco_ml" role="button">
                Mentions légales
                </a>
                <!-- partie mentions légales sous la forme d'un modal -->
                <div class="modal fade" id="popco_ml" tabindex="-1" aria-labelledby="popco_mlLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content bg-ctm-terciary-color">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="popco_mlLabel">MENTIONS LÉGALES</h1>
                            <button type="button" class="btn-close link-ctm-primary-color-subtle" data-bs-dismiss="modal" aria-label="Close"></button>
                            <!-- bouton pour fermer les mentions légales (en forme de X)-->
                        </div>
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-ctm-secondary-color-subtle" data-bs-dismiss="modal">Close</button>
                            <!-- bouton pour fermer les mentions légales "Close"-->
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>