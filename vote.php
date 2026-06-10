<?php
require_once './bdd/bdd_connexion.php';
$bdd = connectBDS();

$allSoirees = $bdd->query('SELECT * FROM film');

$soireesHorreur_sort = $bdd->query("SELECT * FROM film WHERE genre = 'horreur'; ");
$soireesAction_sort = $bdd->query("SELECT * FROM film WHERE genre = 'action'; ");
$soireesFanstastique_sort = $bdd->query("SELECT * FROM film WHERE genre = 'fantastique'; ");
$soireesAnimation_sort = $bdd->query("SELECT * FROM film WHERE genre = 'Animation'; ");
$soireesComedy_sort = $bdd->query("SELECT * FROM film WHERE genre = 'Comédie'; ");
$soireesHistorique_sort = $bdd->query("SELECT * FROM film WHERE genre = 'Historique'; ");
$soireesThriller_sort = $bdd->query("SELECT * FROM film WHERE genre = 'Thriller'; ");


// Documentaty is broken for some reasons (Please Check this Astrid !), Wrong Token or invalid expression 
// $soireesDocumentaire_sort = $bdd->query("SELECT * FROM film WHERE genre = 'Documentaire'; "); 

$soireesRomance_sort = $bdd->query("SELECT * FROM film WHERE genre = 'Romance'; ");
$soireesSF_sort = $bdd->query("SELECT * FROM film WHERE genre = 'Science-Fiction'; ");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/icons/PopCo_favicon.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styles/main.css">
    <!-- Font Awesome pour les icônes -->
        <script src="https://kit.fontawesome.com/4b69bc6b92.js" crossorigin="anonymous"></script>
    <!-- Bootstrap Icons  -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Les soirées</title>
</head>

<body class="bg-ctm-terciary-color">
    <header>
         <!-- Header contenant le menu de navigation version pour écran normal et version pour écran réduit -->
        <div class="container-fluid p-0">
                <nav id="header_popco" class="navbar navbar-expand bg-ctm-primary-color rounded-bottom-5 ">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="./index.php">
                            <img src="./assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
                            <!-- Insertion de l'icône du logo PopCo -->
                        </a>
                        <div class="collapse navbar-collapse justify-content-between">
                            <ul class="navbar-nav mb-2 mb-lg-0 d-none d-md-flex">
                                <!-- class de la barre de navigation (navbar) avec une marge de bas de 2 et de 0 à partir du breakpoint large -->
                                <li class="nav-item active">
                                    <a class="nav-link" href="./index.php">Accueil</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./soirees.php">Les soirées</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./soiree_create.php">Créer une soirée</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./films.php">Films proposés</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./utilisateur.php">Utilisateur</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./vote.php">Vote</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav mb-2 mb-lg-0 gap-2 me-0 d-none d-md-flex">
                                <li class="nav-item">
                                    <a class="btn btn-ctm-red-subtle" href="./connexion.php">Se connecter</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-ctm-red" href="./new_account.php">Créer un compte</a>
                                </li>
                            </ul>
                        </div>
                        <!-- les liens vers le pages dans le menu de navigation et des boutons pour la connexion et la création d'un compte -->
                        <a class="fs-1 d-block d-md-none text-success" data-bs-toggle="offcanvas" href="#menu_phone" aria-controls="offcanvasExample">
                        <i class="bi bi-list link-ctm-terciary-color"></i>
                        </a>
                        <div class="offcanvas-md d-md-none offcanvas-end bg-ctm-terciary-color" tabindex="-1" id="menu_phone" aria-labelledby="menu_phoneLabel">
                            <!-- ajout de la class offcanvas pour créer le menu burger (sur la version réduite du site) -->
                            <div class="offcanvas-header">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="menu_phoneLabel">PopCo</h5>
                                <button type="button" class="btn-close btn-ctm-primary-color-subtle" data-bs-dismiss="offcanvas" data-bs-target="#menu_phone" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body d-flex flex-column justify-content-between px-0">
                                <ul class="list-group">
                                    <a href="./index.php" class="list-group-item list-group-item-action" aria-current="true">
                                        Accueil
                                    </a>
                                    <a href="./soirees.php" class="list-group-item list-group-item-action">
                                        Les soirées
                                    </a>
                                    <a href="./soiree_create.php" class="list-group-item list-group-item-action">
                                        Créer une soirée
                                    </a>
                                    <a href="./films.php" class="list-group-item list-group-item-action">
                                        Films proposés
                                    </a>
                                    <a href="./utilisateur.php" class="list-group-item list-group-item-action">
                                        Utilisateur
                                    </a>
                                    <a href="./vote.php" class="list-group-item list-group-item-action active list-group-item-ctm-terciary-color-subtle">
                                        Voter
                                    </a>
                                </ul>
                                 <!-- les liens vers le pages dans le menu de navigation version burger-->
                                <div class="container-fluid d-md-flex justify-content-end gap-2">
                                    <a class="btn btn-ctm-red-subtle" href="./connexion.php">Se connecter</a>
                                    <a class="btn btn-ctm-red" href="./new_account.php">Créer un compte</a>
                                    <!-- boutons pour la connexion et la créationd'un compte -->
                                </div>
                            </div>
                        </div>

                    </div>
                </nav>
        </div>
    </header>

    <main>
        <!-- partie du vote-->
        <div class="container my-5" id="formulaireVote">
            <h5 class="fs-3">Votez pour le film que vous aimeriez voir !</h5>
            <p>Ci-dessous le choix de films que l'organisateur de soirée à décider à sélectionner pour la soirée du jour mois année à heure heure</p>

            <p>Pour la soirée [soirée], vous avez le choix entre les films suivants, veuillez en choisir un.</p>
            <form>
                <div class="container d-flex justify-content-between flex-wrap">
                    <div class="col-12 col-md-6 col-lg-4">
                        <input type="radio" class="btn-check" id="btn-check-1" autocomplete="off" name="vote">
                        <label class="btn btn-lg" for="btn-check-1">
                            <figure>
                                <img src="./assets/images/rin_smile.png" class="figure-img img-fluid object-fit-cover" alt="...">
                                <figcaption class="figure-caption text-ctm-primary-color-subtle">Film 1</figcaption>
                            </figure>
                        </label>
                    </div>
                    <!-- premier bouton pour le choix 1 -->
    
                    <div class="col-12 col-md-6 col-lg-4">
                        <input type="radio" class="btn-check" id="btn-check-2" autocomplete="off" name="vote">
                        <label class="btn btn-lg" for="btn-check-2">
                            <figure class="align-content-center">
                                <img src="./assets/images/miku_sunset.png" class="figure-img img-fluid object-fit-cover" alt="...">
                                <figcaption class="figure-caption text-ctm-primary-color-subtle">Film 2</figcaption>
                            </figure>
                        </label>
                    </div>
                    <!-- deuxième bouton pour le choix 2 -->
    
                    <div class="col-12 col-md-6 offset-md-3 col-lg-4 offset-lg-0">
                        <input type="radio" class="btn-check" id="btn-check-3" autocomplete="off" name="vote">
                        <label class="btn btn-lg" for="btn-check-3">
    
                            <figure class="align-content-center">
                                <img src="./assets/images/AAAHHH.png" class="figure-img img-fluid object-fit-cover" alt="...">
                                <figcaption class="figure-caption text-ctm-primary-color-subtle">Film 3</figcaption>
                            </figure>
                        </label>
                    </div>
                    <!-- troisième bouton pour le choix 3 -->
                    <button type="submit" class="btn btn-ctm-red mt-3 container-fluid mb-5 p-3">Voter</button>
                    <!-- bouton de type submit pour valider le vote -->
                </div>
            </div>
    
            </form>
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
                <img src="./assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
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