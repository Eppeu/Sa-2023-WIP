<?php
if (!isset($_POST['search_movie'])) {
    session_start();
    if(!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != TRUE) header("Location: ../public/index.php");
} 

require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();
$error = 0;

// Récupère les informations de l'utilisateur s'il est connecté
if(isset($_SESSION['email'])){
    $utilisateur_infos_requete = $bdd->prepare("SELECT * FROM utilisateur WHERE email=?");
    $utilisateur_infos_requete->execute(array($_SESSION['email']));
    $utilisateur_infos = $utilisateur_infos_requete->fetch();
}

$allSoirees = $bdd->query('SELECT * FROM film');

if (isset($_POST['search_movie'])) include("api_contact.php");

function add_movie($name_movie,$nom_filmPOST,$synopsisPOST,$genrePOST,$date_sortiePOST,$affichePOST) {
    global $bdd;
    $name_movie_string = "'". $name_movie . "'";
    $sql_command = 'SELECT * FROM film WHERE nom_film =' . $name_movie_string . ';';
    $check_movie = $bdd->query($sql_command);
    $Get_movie_BD = $check_movie->fetch();
    if ($Get_movie_BD['nom_film'] == NULL) {

        $nom_film = nl2br(htmlspecialchars($nom_filmPOST));
        $synopsis = nl2br(htmlspecialchars($synopsisPOST));
        $genre = nl2br(htmlspecialchars($genrePOST));
        $date_sortie = nl2br(htmlspecialchars($date_sortiePOST));
        $affiche = nl2br(htmlspecialchars($affichePOST));


        $movie_add = $bdd->prepare(
            "INSERT INTO film(nom_film,synopsis,genre,date_sortie,affiche)
                VALUES(?, ?, ?, ?, ?)"
        );
        $movie_add->execute([
            $nom_film,
            $synopsis,
            $genre,
            $date_sortie,
            $affiche
        ]);

        header("Location: ./movie_create.php");
        exit();
    } else {
        $error = 1;
        echo '
        <div class="alert alert-danger m-0" role="alert">
            Le film en question existe déjà dans la base de donnée.
        </div>
        ';
    }
    }

if (isset($_POST['movie_create'])) {
    $nom_filmPOST = $_POST['nom_filmPOST'];
    $synopsisPOST = $_POST['synopsisPOST'];
    $genrePOST = $_POST['genrePOST'];
    $date_sortiePOST = $_POST['date_sortiePOST'];
    $affichePOST = $_POST['affichePOST'];
    $name_movie = $_POST['name_movie'];
    add_movie($name_movie,$nom_filmPOST,$synopsisPOST,$genrePOST, $date_sortiePOST,$affichePOST);
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
    <title>Nouveau Film</title>

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
                                        <a class="nav-link bootstrap_nav_item_color" href="./film_create">Ajouter un film</a>
                                        <!-- lien de navigation -->
                                    </li>
                                    <?php } ?>
                                <?php } ?>

                            </ul>

                            <?php
                            if(isset($_SESSION['email'])) {
                                ?>
                                <div class="dropdown dropstart">
                                <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <h2><i class="bi bi-person fs-3 link-ctm-terciary-color-subtle me-4"></i></h2>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="mx-3"><?= $utilisateur_infos['nom_utilisateur'];?> <?= $utilisateur_infos['prenom_utilisateur'];?></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="../public/utilisateur">Votre profil</a></li>
                                    <li><a class="dropdown-item" href="./deconnexion">Se déconnecter</a></li>
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
                                    <a href="./index" class="list-group-item list-group-item-action active list-group-item-ctm-terciary-color-subtle" aria-current="true">
                                        Accueil
                                        <!-- list group actif -->
                                    </a>
                                    <a href="../public/soirees" class="list-group-item list-group-item-action">
                                        Les soirées
                                    </a>>
                                    <a href="../public/films.php" class="list-group-item list-group-item-action">
                                        Films proposés
                                    </a>
                                    <?php if(isset($_SESSION['email'])) { ?>
                                    <a href="../public/soiree_create" class="list-group-item list-group-item-action">
                                        Film
                                    </a>
                                    <?php } ?>
                                    <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']==TRUE) { ?>
                                        <a class="list-group-item list-group-item-action" href="./film_create">
                                            Ajouter un film
                                        </a>
                                    <?php } ?>
                                </ul>

                                <?php
                                if(isset($_SESSION['email'])) {
                                    ?>
                                    <div class="dropdown dropstart">
                                        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <h2><i class="bi bi-person fs-3 link-ctm-terciary-color-subtle me-4"></i></h2>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="mx-3"><?= $utilisateur_infos['nom_utilisateur'];?> <?= $utilisateur_infos['prenom_utilisateur'];?></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="../public/utilisateur">Votre profil</a></li>
                                            <li><a class="dropdown-item" href="./deconnexion">Se déconnecter</a></li>
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
        <div class="text-center my-5 py-5">
            <h5>Ajoutez un nouveau film</h5>
        </div>
        <h5 class="ms-5">Entrez les informations du film à ajouter.</h5>

        <div class="container my-5">

            <?php 
            if ($error === 1) { 
                echo '
                <div class="alert alert-danger" role="alert">
                    Le film en question existe déjà dans la base de donnée.
                </div>
                ';

            } ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <!-- partie formulaire -->

                 <!-- Nom de la soirée -->
                <div class="mb-3">
                    <label for="nomMovie" class="form-label">Nom du film:</label>
                    <input type="text" class="form-control" name="nomMovie" id="nomMovie" maxlength="30" placeholder="Backrooms">
                </div>

                <button name="search_movie" type="submit" class="btn btn-ctm-red">Chercher</button>
                <!-- bouton pour soumettre la soirée -->
            </form>

            
            <?php if (isset($_POST['search_movie'])) { ?>
            
                <div id="temp_div" class ="row mt-3">
                    <div class ="col-4">
                        <figure class ="figure">
                            <img src=<?= "https://image.tmdb.org/t/p/w500" . $data_fr["poster_path"]?> class="figure-img img-fluid rounded img-create" alt="...">
                        </figure>
                        
                    </div>

                    <div class="col-8">
                        <div class ="row">
                            <h1 class="col-6"><?=$data_fr["title"]?></h1>
                            <h3 class="col-3"><?=$data_fr["genres"][0]["name"]?></h3>
                            <h3 class="col-3"><?=substr($data_fr["release_date"], 0, 4)?></h3>
                        </div>

                        <div class ="row">
                            <p><?=$data_fr["overview"]?></p>
                        </div>
                    </div>
                    <h4 class ="mb-1">Est-ce le bon film ?</h4>
                    <div class ="row gap-3">
                        <form action="" method="POST">
                            <input type="hidden" name="nom_filmPOST" value="<?= htmlspecialchars($data_fr["title"]) ?>">
                            <input type="hidden" name="synopsisPOST" value="<?= htmlspecialchars($data_fr["overview"]) ?>">
                            <input type="hidden" name="genrePOST" value="<?= htmlspecialchars($data_fr["genres"][0]["name"]) ?>">
                            <input type="hidden" name="date_sortiePOST" value="<?= htmlspecialchars(substr($data_fr["release_date"], 0, 4)) ?>">
                            <input type="hidden" name="affichePOST" value="<?= htmlspecialchars("https://image.tmdb.org/t/p/w500" . $data_fr["poster_path"]) ?>">
                            <input type="hidden" name="name_movie" value="<?= htmlspecialchars($name_movie) ?>">

                            <button name="movie_create" type="submit" class="btn btn-ctm-red col-4">Ajouter</button>
                            <button name="movie_empty" type="button" class="btn btn-ctm-red-subtle col-4">Non (réessayer)</button>
                        </form>
                    </div>
                </div>

                <script>
                $(document).ready(function() {
                    $("button[name='search_movie']").css('display','none');
                    $("button[name='movie_empty']").click(function() {
                        $("#temp_div").empty();
                        $("button[name='search_movie']").css('display','block');
                    })
                });
                </script>

                
                
            <?php } ?>
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
                <!--Insertion de l'icône du logo PopCo -->
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