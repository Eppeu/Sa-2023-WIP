<!-- 
films.php - PopCo - Films
Cette page permet de trier les différents films de la base de données a travers leur genre.
-->

<?php
include('../include_code/connect_db.php');


// Tous les films triés par leur genre via une commande SQL. 
$allSoirees = $bdd->query('SELECT * FROM film');
$soireesHorreur_sort = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'horreur'; ");
$soireesAction_sort = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'action'; ");
$soireesFanstastique_sort = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'fantastique'; ");
$soireesAnimation_sort = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'Animation'; ");
$soireesComedy_sort = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'Comédie'; ");
$soireesHistorique_sort = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'Historique'; ");
$soireesThriller_sort = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'Thriller'; ");
$soireesDocumentaire_sort = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'Documentaire'; ");
$soireesRomance_sort = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'Romance'; ");
$soireesSF_sort = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'Science-Fiction'; ");


// Fonction qui permet d'écrie en une fois les élements affichés dans un slider dépendament de son genre.
function generateCard_Movie($DBData) {
    echo 
    '<div class="row mx-3">
        <div id="scrollbar" class="col-12 overflow-x-scroll me-5">
            <div id ="img-resize" class="row row-cols-2 row-cols-md-5 ms-1 my-3 g-5 flex-nowrap gap-3">';
                while($DBInfo = $DBData->fetch()){
                    echo '<div class="card p-0 m-0">
                        <img src=' . $DBInfo["affiche"] . ' class="replace-img card-img-top object-fit-cover" alt="...">

                        <div class="card-body bg-ctm-primary-color-subtle">
                            <h5 class="card-title">' . $DBInfo["nom_film"] . '</h5>
                            <p class="card-text lh-1">' . $DBInfo["LEFT(synopsis, 200)"] . '...<p>
                        </div>
                        <div class="card-footer p-0 border-0">
                            <a href="./film_infos.php?id_film=' . $DBInfo["id_film"] . '"' . 'class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">En savoir plus</a>
                        </div>
                    </div>';
                }
        echo '</div>
        </div>
    </div>';
}
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
    <title>Les films</title>

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
                                <li class="nav-item">
                                    <!-- item de navigation actif -->
                                    <a class="nav-link" href="../public/index.php">Accueil</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="../public/soirees">Soirées</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item active">
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
                                    <a href="./index" class="list-group-item list-group-item-action" aria-current="true">
                                        Accueil
                                    </a>
                                    <a href="./soirees" class="list-group-item list-group-item-action">
                                        Soirées
                                    </a>
                                    <a href="./films.php" class="list-group-item list-group-item-action active list-group-item-ctm-terciary-color-subtle">
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

    <main class="container-fluid px-0">
        <div class="bgImage3"></div>
        <div class="text-center py-5 callToAction">
            <h5 class="fs-1">La liste des films à choisir !</h5>
            <p class="mt-5 fs-4">
                Retrouvez ci-dessous la liste des films que PopCo vous permet de choisir !
            </p>
        </div>

        <div class="mainPart py-5">
            <!-- parties avec la liste des genres et leur film correspondant sous forme de card avec une barre de défilement
             en utilisant la fonction crée auparavant. -->
            <h5 class="ms-5 fs-3">Les films d'action</h5>
            <?php generateCard_Movie($soireesAction_sort); ?>
            <!-- film d'action fin -->

            <h5 class="ms-5 mt-4 fs-3">Les films fantastiques</h5>
            <?php generateCard_Movie($soireesFanstastique_sort); ?>

            <!-- film d'horreur fin -->
            <h5 class="ms-5 mt-4 fs-3">Les films d'horreur</h5>
            <?php generateCard_Movie($soireesHorreur_sort); ?>

            <!-- film fantastiques fin-->
            <h5 class="ms-5 mt-4 fs-3">Les films d'animation</h5>
            <?php generateCard_Movie($soireesAnimation_sort); ?>

            <!-- film d'animation fin -->
            <h5 class="ms-5 mt-4 fs-3">Les films de comédie</h5>
            <?php generateCard_Movie($soireesComedy_sort); ?>

            <!-- film comédie fin -->
            <h5 class="ms-5 mt-4 fs-3">Les films historiques</h5>
            <?php generateCard_Movie($soireesHistorique_sort); ?>

            <!-- film historique fin -->
            <h5 class="ms-5 mt-4 fs-3">Les films de thriller</h5>
            <?php generateCard_Movie($soireesThriller_sort); ?>

            <!-- film thriller fin -->
            <h5 class="ms-5 mt-4 fs-3">Les films de Romance</h5>
            <?php generateCard_Movie($soireesRomance_sort); ?>

            <!-- film romance fin -->
            <h5 class="ms-5 mt-4 fs-3">Les films de Science Fiction</h5>
            <?php generateCard_Movie($soireesSF_sort); ?>
            <!-- film science fiction fin -->

            <h5 class="ms-5 mt-4 fs-3">Les Documentaires</h5>
            <?php generateCard_Movie($soireesDocumentaire_sort); ?>
        </div>

    </main>

    <!-- Footer via un include afin de ne pas avoir de code répété  -->
    <footer id="footer_popco" class="container-fluid py-3 rounded-top-5 bg-ctm-primary-color">
        <?php include("../include_code/footer.php");?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>