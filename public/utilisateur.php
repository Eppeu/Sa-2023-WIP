<!-- CONNEXION A LA BASE DE DONNEE-->
<?php
session_start();
if(!$_SESSION['nom_utilisateur']) {
    header('Location: ./connexion.php');
}

require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();


// Sélection de tous les soirées (films)
// $allUtilisateur = $bdd->query('SELECT * FROM utilisateur WHERE nom_utilisateur=""');

$query = $bdd->prepare("SELECT * FROM utilisateur WHERE nom_utilisateur=:identifiant");
$query->execute([':identifiant' => $_SESSION['nom_utilisateur']]);
$infosUtilisateur = $query->fetch();
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
    <title>Les soirées</title>
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
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./vote.php">Vote TEMP</a>
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
                            if(isset($_SESSION['nom_utilisateur'])) {
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
                                    <a href="./vote.php" class="list-group-item list-group-item-action">
                                        Voter
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

    <main class="container-fluid px-0">
        <div class="bgImage"></div>
        <div class="text-center py-5 callToAction">
            <h5 class="fs-1">Bonjour <?= $infosUtilisateur['nom_utilisateur'];?> !</h5>
        </div>
        <!-- affichage de la page avec texte -->
        <div class="mainPart py-5">
            <div class="row m-0">
                <div class="ms-5 col-11">
                    <h5 class="fs-3">Vos informations</h5>
                    <p class="mt-5">Nom : <?= $infosUtilisateur['nom_utilisateur'];?></p>
                    <p>Prénom : <?= $infosUtilisateur['prenom_utilisateur'];?></p>
                    <p>email : <?= $infosUtilisateur['email'];?></p>
                    <h5 class="mt-5 fs-3">Les soirées auxquelles vous êtes inscrit(e)</h5>
                </div>
                <!-- affiche les informations de l'utilisateur -->
            <div id ="img-resize" class="row row-cols-5 mx-0 mb-3 pt-3 g-5">
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3" >
                    <div class="card p-0 h-auto">
                        <img src="../assets/images/rin_smile.png" class="card-img-top object-fit-cover" alt="...">

                        <div class="card-body bg-ctm-primary-color-subtle">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text lh-1">Some quick example text to build on the card title and make up the bulk of the card’s content.<p>
                        </div>
                        <div class="card-footer p-0 border-0">
                            <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">Go somewhere</a>
                        </div>
                    </div>
                </div>
            
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card p-0 h-auto">
                        <img src="../assets/images/yuri_time.png" class="card-img-top object-fit-cover" alt="...">

                        <div class="card-body bg-ctm-primary-color-subtle">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text lh-1">Some quick example text to build on the card title and make up the bulk of the card’s content.<p>
                        </div>
                        <div class="card-footer p-0 border-0">
                            <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">Go somewhere</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card p-0 h-auto">
                        <img src="../assets/images/AAAHHH.png" class="card-img-top object-fit-cover" alt="...">

                        <div class="card-body bg-ctm-primary-color-subtle">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text lh-1">Some quick example text to build on the card title and make up the bulk of the card’s content.<p>
                        </div>
                        <div class="card-footer p-0 border-0">
                            <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">Go somewhere</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card p-0 h-auto">
                        <img src="../assets/images/chippies.png" class="card-img-top object-fit-cover" alt="...">

                        <div class="card-body bg-ctm-primary-color-subtle">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text lh-1">Some quick example text to build on the card title and make up the bulk of the card’s content.<p>
                        </div>
                        <div class="card-footer p-0 border-0">
                            <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">Go somewhere</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card p-0 h-auto">
                        <img src="../assets/images/rella_16th_birthday_edit.jpg" class="card-img-top object-fit-cover" alt="...">

                        <div class="card-body bg-ctm-primary-color-subtle">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text lh-1">Some quick example text to build on the card title and make up the bulk of the card’s content.<p>
                        </div>
                        <div class="card-footer p-0 border-0">
                            <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">Go somewhere</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card p-0 h-auto">
                        <img src="../assets/images/looking_into_my_soul.png" class="card-img-top object-fit-cover" alt="...">

                        <div class="card-body bg-ctm-primary-color-subtle">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text lh-1">Some quick example text to build on the card title and make up the bulk of the card’s content.<p>
                        </div>
                        <div class="card-footer p-0 border-0">
                            <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">Go somewhere</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card p-0 h-auto">
                        <img src="../assets/images/miku_sunset.png" class="card-img-top object-fit-cover" alt="...">

                        <div class="card-body bg-ctm-primary-color-subtle">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text lh-1">Some quick example text to build on the card title and make up the bulk of the card’s content.<p>
                        </div>
                        <div class="card-footer p-0 border-0">
                            <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">Go somewhere</a>
                        </div>
                    </div>
                </div>
                <!-- carte des soirée ou l'utilisateur participe -->
            </div>
        </div>
    </main>

    <footer id="footer_popco" class="container-fluid py-3 rounded-top-5 bg-ctm-primary-color">
        <!-- Footer avec les liens vers instagram, discord, facebook, mentions légales -->
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