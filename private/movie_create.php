<?php
session_start();
require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();

$allSoirees = $bdd->query('SELECT * FROM film');

if (!empty($_POST)) include("api_contact.php");

function add($nomSoireePOST, $descriptionSoireePOST, $genreSoireePOST, $choixFilm1POST, $choixFilm2POST, $choixFilm3POST, $choixFilm4POST, $choixFilm5POST, $choixLieu1POST, $choixLieu2POST, $choixLieu3POST, $nb_personne_maxPOST, $date_debutPOST, $date_finPOST) {

    global $bdd;

    // DEBUG TEMPORAIRE - à supprimer une fois le problème résolu
    echo "<pre>";
    echo "nomSoiree : '" . $nomSoireePOST . "'\n";
    echo "DESCRIPTION : '" . $descriptionSoireePOST . "'\n";
    echo "choixFilm1 : '" . $choixFilm1POST . "'\n";
    echo "choixFilm2 : '" . $choixFilm2POST . "'\n";
    echo "choixFilm3 : '" . $choixFilm3POST . "'\n";
    echo "choixFilm4 : '" . $choixFilm4POST . "'\n";
    echo "choixFilm5 : '" . $choixFilm5POST . "'\n";
    echo "choixLieu1 : '" . $choixLieu1POST . "'\n";
    echo "choixLieu2 : '" . $choixLieu2POST . "'\n";
    echo "choixLieu3 : '" . $choixLieu3POST . "'\n";
    echo "nb_personne_max : '" . $nb_personne_maxPOST . "'\n";
    echo "date_debut : '" . $date_debutPOST . "'\n";
    echo "date_fin : '" . $date_finPOST . "'\n";
    echo "</pre>";
    // FIN DEBUG

    $nomSoiree   = nl2br(htmlspecialchars($nomSoireePOST));
    $descriptionSoiree   = nl2br(htmlspecialchars($descriptionSoireePOST));
    $genreSoiree = nl2br(htmlspecialchars($genreSoireePOST));

    // Bug 5 corrigé : conversion du format datetime-local → MySQL
    $date_debut = str_replace('T', ' ', $date_debutPOST);
    $date_fin   = str_replace('T', ' ', $date_finPOST);

    // Bug 2 corrigé : utilisation de 'formFile' partout
    $time     = date('YmdHis');
    $filename = $time . basename($_FILES["formFile"]["name"]);

    if (
        !empty($nomSoiree) && !empty($descriptionSoiree) && !empty($choixFilm1POST) && !empty($choixFilm2POST) &&
        !empty($choixFilm3POST) && !empty($choixFilm4POST) && !empty($choixFilm5POST) &&
        !empty($nb_personne_maxPOST) && !empty($date_debut) && !empty($date_fin) &&
        !empty($choixLieu1POST) && !empty($choixLieu2POST) && !empty($choixLieu3POST)
    ) {
        // Déplacement de l'image
        $path_directory = "../assets/public/";
        $file_directory = $path_directory . $filename;
        if (move_uploaded_file($_FILES["formFile"]["tmp_name"], $file_directory)) {
            $image_path = "../assets/public/" . $filename;
        } else {
            echo "Erreur lors du téléchargement de l'image.";
            return;
        }

        // Bug 3 corrigé : ordre des valeurs aligné sur l'ordre des colonnes INSERT
        // Bug 4 corrigé : remplacement de ??????????? par image_soiree
        $ajoutSoiree = $bdd->prepare(
            "INSERT INTO soiree(nom_soiree, description_soiree, nb_personne_max, genre_soiree, date_debut, date_fin,
             choix_1_film, choix_2_film, choix_3_film, choix_4_film, choix_5_film,
             choix_1_lieu, choix_2_lieu, choix_3_lieu, image_soiree)
             VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $ajoutSoiree->execute([
            $nomSoiree,         // nom_soiree
            $descriptionSoiree, // description_soiree
            $nb_personne_maxPOST, // nb_personne_max
            $genreSoiree,       // genre_soiree
            $date_debut,        // date_debut
            $date_fin,          // date_fin
            $choixFilm1POST,    // choix_1_film
            $choixFilm2POST,    // choix_2_film
            $choixFilm3POST,    // choix_3_film
            $choixFilm4POST,    // choix_4_film
            $choixFilm5POST,    // choix_5_film
            $choixLieu1POST,    // choix_1_lieu
            $choixLieu2POST,    // choix_2_lieu
            $choixLieu3POST,    // choix_3_lieu
            $image_path         // image_soiree
        ]);

        header('Location: ./soirees.php');
        exit();

    } else {
        echo "Veuillez compléter tous les champs.";
    }
}

// Bug 1 corrigé : appel à add() décommenté
if (isset($_POST["create_party"])) {
    add(
        $_POST['nomSoiree'],
        $_POST['description_soiree'],
        $_POST['genre_movie'],
        $_POST['choixFilm1'],
        $_POST['choixFilm2'],
        $_POST['choixFilm3'],
        $_POST['choixFilm4'],
        $_POST['choixFilm5'],
        $_POST['choixLieu1'],
        $_POST['choixLieu2'],
        $_POST['choixLieu3'],
        $_POST['nb_personne_max'],
        $_POST['date_debut'],
        $_POST['date_fin']
    );
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
                                        <a class="btn btn-ctm-red" href="./compte_create.php">Créer un compte</a>
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
        <div class="text-center my-5 py-5">
            <h5>Ajoutez un nouveau film</h5>
        </div>
        <h5 class="ms-5">Entrez les informations du film à ajouter.</h5>

        <div class="container my-5">

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

            
            <?php if (!empty($_POST)) { ?>
            
                <div class ="row mt-3">
                    <div class ="col-4">
                        <figure class ="figure">
                            <img src=<?= "https://image.tmdb.org/t/p/w500" . $data_fr["poster_path"]?> class="figure-img img-fluid rounded img-create" alt="...">
                        </figure>
                        
                    </div>

                    <div class="col-8">
                        <div class ="row">
                            <h1 class="col-6"><?=$data_fr["original_title"]?></h1>
                            <h3 class="col-3"><?=$data_fr["genres"][0]["name"]?></h3>
                            <h3 class="col-3"><?=$data_fr["release_date"]?></h3>
                        </div>

                        <div class ="row">
                            <p><?=$data_fr["overview"]?></p>
                        </div>
                    </div>
                    <h4 class ="mb-1">Est-ce le bon film ?</h4>
                    <div class ="row gap-3">
                        <button name="movie_create" type="submit" class="btn btn-ctm-red col-4">Ajouter</button>
                        <button name="empty_movie" type="submit" class="btn btn-ctm-red-subtle col-4">Non (réessayer)</button>
                    </div>
                </div>
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