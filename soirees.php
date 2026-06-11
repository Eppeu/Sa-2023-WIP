<?php
session_start();

require_once './bdd/bdd_connexion.php';
$bdd = connectBDS();

$allSoirees = $bdd->query('SELECT * FROM soiree');

$soireesHorreur_sort = $bdd->query("SELECT * FROM soiree WHERE genre_soiree = 'Horreur';");
$soireesAction_sort = $bdd->query("SELECT * FROM soiree WHERE genre_soiree = 'Action';");
$soireesFanstastique_sort = $bdd->query("SELECT * FROM soiree WHERE genre_soiree = 'Fantastique';");
$soireesAnimation_sort = $bdd->query("SELECT * FROM soiree WHERE genre_soiree = 'Animation';");
$soireesComedy_sort = $bdd->query("SELECT * FROM soiree WHERE genre_soiree = 'Comédie';");
$soireesHistorique_sort = $bdd->query("SELECT * FROM soiree WHERE genre_soiree = 'Historique';");
$soireesThriller_sort = $bdd->query("SELECT * FROM soiree WHERE genre_soiree = 'Thriller';");


// Documentaty is broken for some reasons (Please Check this Astrid !), Wrong Token or invalid expression 
// $soireesDocumentaire_sort = $bdd->query("SELECT * FROM film WHERE genre = 'Documentaire'; "); 

$soireesRomance_sort = $bdd->query("SELECT * FROM soiree WHERE genre_soiree = 'Romance';");
$soireesSF_sort = $bdd->query("SELECT * FROM soiree WHERE genre_soiree = 'Science-Fiction'; ");
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
                            </ul>

                            <?php
                            if(isset($_SESSION['nom_utilisateur'])) {
                                ?>
                                <ul class="navbar-nav mb-2 mb-lg-0 gap-2 me-0 d-none d-md-flex">
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red-subtle" href="./utilisateur.php">Votre profil</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red" href="./deconnexion.php">Se déconnecter</a>
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
                                        <a class="btn btn-ctm-red" href="./new_account.php">Créer un compte</a>
                                    </li>
                                </ul>
                                <!-- Boutons Rouges (un de couleur légère et l'autre non) pour créer un compte et se connecter -->
                                
                        </div>
                        <?php
                            }
                            ?>

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
                                    <a class="btn btn-ctm-red" href="./new_account.php">Créer un compte</a>
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
            <h5 id="test" class="fs-1">Retrouvez toutes les soirées ici !</h5>
        </div>
        <!-- affichage principal avec titre -->
        <div class="mainPart py-5">
            <div class="row m-0">
                <div class="col-8">
                    <h5 class="ms-5 fs-3">Les dernières soirées ajoutées</h5>
                </div>
                <div class="col-auto">
                    <select name="movie_genre" id="genre_select" onchange='$.fn.load_new_content()' class="form-select ms-2" aria-label="Default select example">
                        <option selected>Trier par genre...</option>
                        <option value="Action">Action</option>
                        <option value="Fantastique">Fantastique</option>
                        <option value="Horreur">Horreur</option>
                        <option value="Animation">Animation</option>
                        <option value="Comédie">Comédie</option>
                        <option value="Historique">Historique</option>
                        <option value="Thriller">Thriller</option>
                        <option value="Documentaire">Documentaire</option>
                        <option value="Romance">Romance</option>
                        <option value="Science-Fiction">Science-Fiction</option>
                    </select>
                </div>
            </div>
            <!-- formulaire de selection en fonction des genres -->

            <!-- Méthode Ajax afin de load la BDD (Semi-Broken) -->
            <script>
                $(document).ready(function() {
                    $.fn.load_new_content = function(){
                        var model=$('#genre_select').val();
                        if (model == "Action") {
                            $("#img-resize").html("<?php while($soireesAction_sortInfos = $soireesAction_sort->fetch()){ ?> <div class='col-12 col-sm-6 col-lg-4 col-xl-3'><div class='card p-0 h-auto'><img src='./assets/images/looking_into_my_soul.png' class='card-img-top object-fit-cover' alt='...'><div class='card-body bg-ctm-primary-color-subtle'><h5 class='card-title'><?php echo addslashes($soireesAction_sortInfos['nom_soiree']);?></h5><p class='card-text lh-1'><?php echo addslashes($soireesAction_sortInfos['genre_soiree']);?>...</p></div> <div class='card-footer p-0 border-0'> <a href='#' class='btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1'>Go somewhere</a> </div> </div></div> <?php } ?>");
                        } else if (model == "Fantastique") {
                            $("#img-resize").html("<?php while($soireesFanstastique_sortInfos = $soireesFanstastique_sort->fetch()){ ?> <div class='col-12 col-sm-6 col-lg-4 col-xl-3'><div class='card p-0 h-auto'><img src='./assets/images/looking_into_my_soul.png' class='card-img-top object-fit-cover' alt='...'><div class='card-body bg-ctm-primary-color-subtle'><h5 class='card-title'><?php echo addslashes($soireesFanstastique_sortInfos['nom_soiree']);?></h5><p class='card-text lh-1'><?php echo addslashes($soireesFanstastique_sortInfos['genre_soiree']);?>...</p></div> <div class='card-footer p-0 border-0'> <a href='#' class='btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1'>Go somewhere</a> </div> </div></div> <?php } ?>");
                        } else if (model == "Horreur") {
                            $("#img-resize").html("<?php while($soireesHorreur_sortInfos = $soireesHorreur_sort->fetch()){ ?> <div class='col-12 col-sm-6 col-lg-4 col-xl-3'><div class='card p-0 h-auto'><img src='./assets/images/looking_into_my_soul.png' class='card-img-top object-fit-cover' alt='...'><div class='card-body bg-ctm-primary-color-subtle'><h5 class='card-title'><?php echo addslashes($soireesHorreur_sortInfos['nom_soiree']);?></h5><p class='card-text lh-1'><?php echo addslashes($soireesHorreur_sortInfos['genre_soiree']);?>...</p></div> <div class='card-footer p-0 border-0'> <a href='#' class='btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1'>Go somewhere</a> </div> </div></div> <?php } ?>");
                        } else if (model == "Animation") {
                            $("#img-resize").html("<?php while($soireesAnimation_sortInfos = $soireesAnimation_sort->fetch()){ ?> <div class='col-12 col-sm-6 col-lg-4 col-xl-3'><div class='card p-0 h-auto'><img src='./assets/images/looking_into_my_soul.png' class='card-img-top object-fit-cover' alt='...'><div class='card-body bg-ctm-primary-color-subtle'><h5 class='card-title'><?php echo addslashes($soireesAnimation_sortInfos['nom_soiree']);?></h5><p class='card-text lh-1'><?php echo addslashes($soireesAnimation_sortInfos['genre_soiree']);?>...</p></div> <div class='card-footer p-0 border-0'> <a href='#' class='btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1'>Go somewhere</a> </div> </div></div> <?php } ?>");
                        } else if (model == "Comédie") {
                            $("#img-resize").html("<?php while($soireesComedy_sortInfos = $soireesComedy_sort->fetch()){ ?> <div class='col-12 col-sm-6 col-lg-4 col-xl-3'><div class='card p-0 h-auto'><img src='./assets/images/looking_into_my_soul.png' class='card-img-top object-fit-cover' alt='...'><div class='card-body bg-ctm-primary-color-subtle'><h5 class='card-title'><?php echo addslashes($soireesComedy_sortInfos['nom_soiree']);?></h5><p class='card-text lh-1'><?php echo addslashes($soireesComedy_sortInfos['genre_soiree']);?>...</p></div> <div class='card-footer p-0 border-0'> <a href='#' class='btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1'>Go somewhere</a> </div> </div></div> <?php } ?>");
                        } else if (model == "Historique") {
                            $("#img-resize").html("<?php while($soireesHistorique_sortInfos = $soireesHistorique_sort->fetch()){ ?> <div class='col-12 col-sm-6 col-lg-4 col-xl-3'><div class='card p-0 h-auto'><img src='./assets/images/looking_into_my_soul.png' class='card-img-top object-fit-cover' alt='...'><div class='card-body bg-ctm-primary-color-subtle'><h5 class='card-title'><?php echo addslashes($soireesHistorique_sortInfos['nom_soiree']);?></h5><p class='card-text lh-1'><?php echo addslashes($soireesHistorique_sortInfos['genre_soiree']);?>...</p></div> <div class='card-footer p-0 border-0'> <a href='#' class='btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1'>Go somewhere</a> </div> </div></div> <?php } ?>");
                        } else if (model == "Thriller") {
                            $("#img-resize").html("<?php while($soireesThriller_sortInfos = $soireesThriller_sort->fetch()){ ?> <div class='col-12 col-sm-6 col-lg-4 col-xl-3'><div class='card p-0 h-auto'><img src='./assets/images/looking_into_my_soul.png' class='card-img-top object-fit-cover' alt='...'><div class='card-body bg-ctm-primary-color-subtle'><h5 class='card-title'><?php echo addslashes($soireesThriller_sortInfos['nom_soiree']);?></h5><p class='card-text lh-1'><?php echo addslashes($soireesThriller_sortInfos['genre_soiree']);?>...</p></div> <div class='card-footer p-0 border-0'> <a href='#' class='btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1'>Go somewhere</a> </div> </div></div> <?php } ?>");
                        } else if (model == "Documentaire") { // Is broken, check line 15-16 for more infos
                            $("#img-resize").html(""); 
                        } else if (model == "Romance") {
                            $("#img-resize").html("<?php while($soireesRomance_sortInfos = $soireesRomance_sort->fetch()){ ?> <div class='col-12 col-sm-6 col-lg-4 col-xl-3'><div class='card p-0 h-auto'><img src='./assets/images/looking_into_my_soul.png' class='card-img-top object-fit-cover' alt='...'><div class='card-body bg-ctm-primary-color-subtle'><h5 class='card-title'><?php echo addslashes($soireesRomance_sortInfos['nom_soiree']);?></h5><p class='card-text lh-1'><?php echo addslashes($soireesRomance_sortInfos['genre_soiree']);?>...</p></div> <div class='card-footer p-0 border-0'> <a href='#' class='btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1'>Go somewhere</a> </div> </div></div> <?php } ?>");
                        } else if (model == "Science-Fiction") {
                            $("#img-resize").html("<?php while($soireesSF_sortInfos = $soireesSF_sort->fetch()){ ?> <div class='col-12 col-sm-6 col-lg-4 col-xl-3'><div class='card p-0 h-auto'><img src='./assets/images/looking_into_my_soul.png' class='card-img-top object-fit-cover' alt='...'><div class='card-body bg-ctm-primary-color-subtle'><h5 class='card-title'><?php echo addslashes($soireesSF_sortInfos['nom_soiree']);?></h5><p class='card-text lh-1'><?php echo addslashes($soireesSF_sortInfos['genre_soiree']);?>...</p></div> <div class='card-footer p-0 border-0'> <a href='#' class='btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1'>Go somewhere</a> </div> </div></div> <?php } ?>");
                        }
                    };
                });
            </script>
            <!-- donne les information de chaque film en fonction du genre -->

            <div id ="img-resize" class="row row-cols-5 mx-0 mb-3 pt-3 g-5">
                <?php
                while($allSoireesInfos = $allSoirees->fetch()){
                ?>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3" >
                        <div class="card p-0 h-auto">
                            <img src="./assets/images/rella_16th_birthday_edit.jpg" class="card-img-top object-fit-cover" alt="...">

                            <div class="card-body bg-ctm-primary-color-subtle">
                                <h5 class="card-title"><?= $allSoireesInfos['nom_soiree'];?></h5>
                                <p class="card-text lh-1"><?= $allSoireesInfos['genre_soiree'];?><p>
                            </div>
                            <div class="card-footer p-0 border-0">
                                <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <!-- crée les cartes de présentation pour tout les films -->
                <?php
                }
                ?>

            </div>
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
                <img src="./assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
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