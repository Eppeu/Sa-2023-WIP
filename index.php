<!-- CONNEXION A LA BASE DE DONNEE-->
<?php
error_reporting(0);

if(!isset($_SESSION['nom_utilisateur'])) {
    session_start();
    $connecteeStatus = true;
}

require_once './bdd/bdd_connexion.php';
$bdd = connectBDS();

// Sélection de tous les soirées (films)
$allSoirees = $bdd->query('SELECT *, LEFT(synopsis, 200) FROM film LIMIT 10');

// Comptage du nombre de soirées (films)
$countSoirees = $bdd->prepare('SELECT *, LEFT(synopsis, 200) FROM film');
$countSoirees->execute();
$count = $countSoirees->rowCount();

$soireesPopulaire = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE dateSortie > '2000-01-01'; ");
$soireesHorreur = $bdd->query("SELECT *, LEFT(synopsis, 200) FROM film WHERE genre = 'horreur'; ");

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
    <title>PopCo - Accueil</title>
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
                                    <a class="nav-link bootstrap_nav_item_color" href="./soirees.php">Les soirées</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./soiree_create.php">Créer une soirée</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./films.php">Films proposés</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./utilisateur.php">Utilisateur</a>
                                    <!-- lien de navigation -->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./vote.php">Vote</a>
                                    <!-- lien de navigation -->
                                </li>
                            </ul>

                            <?php
                            if($connecteeStatus) {
                                echo "connecté";
                                
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
                                echo "pas connecté";
                                ?>
                                <ul class="navbar-nav mb-2 mb-lg-0 gap-2 me-0 d-none d-md-flex">
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red-subtle" href="./connexion.php">Se connecter</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red" href="./new_account.php">Créer un compte</a>
                                    </li>
                                    <!-- Boutons Rouges (un de couleur légère et l'autre non) pour créer un compte et se connecter -->
                                </ul>
                            <?php
                            }
                            ?>
                        </div>

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
            <h5 class="fs-1">Organisez des soirées inoubliables !!</h5>
            <p class="px-3 fs-3 sticker bg-ctm-red">Déjà <?php echo $count; ?> soirées créées !</p>

            <p class="mt-5 fs-4">
                Grâce à PopCo, trouvez des soirées où regarder de bons films ! <br/>
                Rien de plus simple : cherchez une soirée, connectez-vous, votez le film qui vous intéresse le plus,<br/>
                et le film qui remporte le plus de voix sera diffusé !<br/>
            </p>
        </div>
        <!-- texte de présentation du site PopCo -->
        <div class="mainPart py-5 z-0">

            <h5 class="ms-5 fs-3 text-ctm-primary-color-subtle">Les soirées récemment ajoutées !</h5>
            <!-- Titre h5 -->
            
            <div class="row mx-3">
                <div id="scrollbar" class="col-12 overflow-x-scroll me-5">
                    <div id ="img-resize" class="row row-cols-2 row-cols-md-5 ms-1 my-3 g-5 flex-nowrap gap-3">
                        <?php
                        while($allSoireesInfos = $allSoirees->fetch()){
                        ?>
                            <div class="card p-0 m-0">
                                <img src="./assets/images/rella_16th_birthday_edit.jpg" class="card-img-top object-fit-cover" alt="...">

                                <div class="card-body bg-ctm-primary-color-subtle">
                                    <h5 class="card-title"><?= $allSoireesInfos['nom_film'];?></h5>
                                    <p class="card-text lh-1"><?= $allSoireesInfos['LEFT(synopsis, 200)'];?>...<p>
                                </div>
                                <div class="card-footer p-0 border-0">
                                    <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">En savoir plus</a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- card pour les soirées récemment ajoutées avec une barre de défilement -->
            <h5 class="ms-5 mt-4 fs-3">Les soirées populaires :fire:</h5>
            <div class="row mx-3">
                <div id="scrollbar" class="col-12 overflow-x-scroll me-5">
                    <div id ="img-resize" class="row row-cols-2 row-cols-md-5 ms-1 my-3 g-5 flex-nowrap gap-3">
                        <?php
                        while($soireesPopulaireInfos = $soireesPopulaire->fetch()){
                        ?>
                            <div class="card p-0 m-0">
                                <img src="./assets/images/rella_16th_birthday_edit.jpg" class="card-img-top object-fit-cover" alt="...">

                                <div class="card-body bg-ctm-primary-color-subtle">
                                    <h5 class="card-title"><?= $soireesPopulaireInfos['nom_film'];?></h5>
                                    <p class="card-text lh-1"><?= $soireesPopulaireInfos['LEFT(synopsis, 200)'];?>...<p>
                                </div>
                                <div class="card-footer p-0 border-0">
                                    <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">En savoir plus</a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- card pour les soirées populaires avec une barre de défilement-->
            <h5 class="ms-5 mt-4  fs-3">Thème film d'Horreur</h5>
            <div class="row mx-3">
                <div id="scrollbar" class="col-12 overflow-x-scroll me-5">
                    <div id ="img-resize" class="row row-cols-2 row-cols-md-5 ms-1 my-3 g-5 flex-nowrap gap-3">
                        <?php
                        while($soireesHorreurInfos = $soireesHorreur->fetch()){
                        ?>
                            <div class="card p-0 m-0">
                                <img src="./assets/images/rella_16th_birthday_edit.jpg" class="card-img-top object-fit-cover" alt="...">

                                <div class="card-body bg-ctm-primary-color-subtle">
                                    <h5 class="card-title"><?= $soireesHorreurInfos['nom_film'];?></h5>
                                    <p class="card-text lh-1"><?= $soireesHorreurInfos['LEFT(synopsis, 200)'];?>...<p>
                                </div>
                                <div class="card-footer p-0 border-0">
                                    <a href="#" class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">En savoir plus</a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- card pour le thème film d'horreur avec une barre de défilement -->
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
                <!-- Insertion de l'icône du logo PopCo -->
            </div>
            <div class="col-4 py-3 text-start d-lg-block text-end pe-4">
                <a class="text-decoration-none link-ctm-terciary-color-subtle" data-bs-toggle="modal" href="#popco_ml" role="button">
                Mentions légales
                </a>
                <div class="modal fade" id="popco_ml" tabindex="-1" aria-labelledby="popco_mlLabel" aria-hidden="true">
                    <!-- partie mentions légales sous la forme d'un modal -->
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