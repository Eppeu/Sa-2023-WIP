<!-- 
soiree_create.php - Créer une soirée 
Sur cette page, un utilisateur qui est connectée peut créeer une soirée ou il doit remplir différents chammps avant de créer 
la soirée.
-->

<?php
session_start();
// Si l'utilisateur n'est pas connecté, il est renvoyé à la page de connexion
if(!$_SESSION['email']) {
    header('Location: ./connexion.php');
}

require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();

// Récupère les informations de l'utilisateur s'il est connecté
if(isset($_SESSION['email'])){
    $utilisateur_infos_requete = $bdd->prepare("SELECT * FROM utilisateur WHERE email=?");
    $utilisateur_infos_requete->execute(array($_SESSION['email']));
    $utilisateur_infos = $utilisateur_infos_requete->fetch();
}

$allSoirees = $bdd->query('SELECT * FROM film');


// Fonction qui permet d'ajouter la soirée rempli dans le formulaire dans la base de donnée en utilisant 
// de la PDO.
function add($nomSoireePOST, $descriptionSoireePOST, $genreSoireePOST, $choixFilm1POST, 
$choixFilm2POST, $choixFilm3POST, $choixFilm4POST, $choixFilm5POST, $choixLieu1POST, $choixLieu2POST, 
$choixLieu3POST, $nb_personne_maxPOST, $date_debutPOST, $date_finPOST,$date_limitPOST) {

    // Appel global car la variable a été créée avant.
    global $bdd;

    $nomSoiree= nl2br(htmlspecialchars($nomSoireePOST));
    $descriptionSoiree = nl2br(htmlspecialchars($descriptionSoireePOST));
    $genreSoiree = nl2br(htmlspecialchars($genreSoireePOST));

    $date_debut = str_replace('T', ' ', $date_debutPOST);
    $date_fin = str_replace('T', ' ', $date_finPOST);
    $date_limite = str_replace('T', ' ', $date_limitPOST);

    // Pour éviter que le nom de l'image soient la même, 
    // on fait en sorte que le fichier aie son temps avant
    $time= date('YmdHis');
    $filename = $time . basename($_FILES["formFile"]["name"]);
    $filename = str_replace(' ', '_', $filename);


    // Condition au niveau des dates pour éviter d'avoir des dates impossiblie 
    if ($date_debut > $date_fin) {
        echo
        '
            <div class="alert alert-light" role="alert">
            La date de début de la soirée est supérieur à la date de fin.
            </div>
        '; 
    } else if ($date_debut < $date_limite) {
        echo
        '
            <div class="alert alert-light" role="alert">
            La date de début de la soirée est supérieur à la date de vote limite.
            </div>
        '; 
    }
    
    if (!empty($nomSoiree) && !empty($descriptionSoiree) && !empty($choixFilm1POST) && !empty($choixFilm2POST) &&
        !empty($choixFilm3POST) && !empty($choixFilm4POST) && !empty($choixFilm5POST) &&
        !empty($nb_personne_maxPOST) && !empty($date_debut) && !empty($date_fin) && !empty($date_limite) &&
        !empty($choixLieu1POST) && !empty($choixLieu2POST) && !empty($choixLieu3POST))
    {
        // Déplacement de l'image
        $path_directory = "../assets/public/";
        $file_directory = $path_directory . $filename;
        if (move_uploaded_file($_FILES["formFile"]["tmp_name"], $file_directory)) {
            $image_path = "../assets/public/" . $filename;
        } else {
            echo "Erreur lors du téléchargement de l'image.";
            return;
        }

        // Ajout du premier lieu à la BDD
        $ajout_lieu_1 = $bdd->prepare("INSERT INTO lieu(adresse) VALUES(?)");
        $ajout_lieu_1->execute([$choixLieu1POST]);
        $id_lieu_1 = $bdd->lastInsertId();

        // Ajout du deuxième lieu à la BDD
        $ajout_lieu_2 = $bdd->prepare("INSERT INTO lieu(adresse) VALUES(?)");
        $ajout_lieu_2->execute([$choixLieu2POST]);
        $id_lieu_2 = $bdd->lastInsertId();

        // Ajout du deuxième lieu à la BDD
        $ajout_lieu_3 = $bdd->prepare("INSERT INTO lieu(adresse) VALUES(?)");
        $ajout_lieu_3->execute([$choixLieu3POST]);
        $id_lieu_3 = $bdd->lastInsertId();

        // Récupère les informations de l'utilisateur connecté
        $utilisateur_infos_requete = $bdd->prepare("SELECT * FROM utilisateur WHERE email=?");
        $utilisateur_infos_requete->execute(array($_SESSION['email']));
        $utilisateur_infos = $utilisateur_infos_requete->fetch();

        $ajoutSoiree = $bdd->prepare("INSERT INTO soiree(id_utilisateur, nom_soiree, description_soiree, nb_personne_max, genre_soiree, date_debut, date_fin, date_limite_vote, choix_1_film, choix_2_film, choix_3_film, choix_4_film, choix_5_film, choix_1_lieu, choix_2_lieu, choix_3_lieu, image_soiree)
             VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $ajoutSoiree->execute([$utilisateur_infos['id_utilisateur'], $nomSoiree, $descriptionSoiree, $nb_personne_maxPOST, $genreSoiree, $date_debut, $date_fin, $date_limite, $choixFilm1POST, $choixFilm2POST, $choixFilm3POST, $choixFilm4POST, $choixFilm5POST, $id_lieu_1, $id_lieu_2, $id_lieu_3, $image_path]);
        $id_soiree = $bdd->lastInsertId();

        header('Location: ./soiree_infos.php?id_soiree='.$id_soiree);
        exit();

    } else {
        echo
        '
            <div class="alert alert-light" role="alert">
            Veuillez remplir tous les champs.
            </div>
        '; 
    }
}

if (isset($_POST["party_confirm"])) {
    add($_POST['nomSoiree'], $_POST['description_soiree'], $_POST['genre_movie'], $_POST['choixFilm1'], $_POST['choixFilm2'], $_POST['choixFilm3'], $_POST['choixFilm4'], $_POST['choixFilm5'], $_POST['choixLieu1'], $_POST['choixLieu2'], $_POST['choixLieu3'], $_POST['nb_personne_max'], $_POST['date_debut'], $_POST['date_fin'], $_POST['date_limite']);
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
    <title>Créer une soirée</title>

<script>
    $(document).ready(function(){
        // Variable qui sont néccéssaire afin de savoir à quel film 
        // l'utilisateur est afin ne pas en ajouter plus.
        let movie_selection = 1;
        let place_selection = 1;

        // AJAX qui permet de faire une recherche en temps réelle de la base donnée afin d'ajouter un film parmi
        // 5 obligatoire à la création d'une soirée. Cette fonction se lance uniquement quand une touche est appuyée.
        $("#livesearch").keyup(function() {
            var result = $("#livesearch").val();
            
            if(result != "") {
                $.ajax({
                    url: "../private/livesearch",
                    type: "POST",
                    data:{result_search:result},
                    success: function(data) {
                        $("#dynamic_search").html(data)
                    }
                });
            }

            // Si la barre de recherche n'a pas de caractères, tout les films sont retirés 
            if ($("#livesearch").val() == "") {
                $("#dynamic_search").html("");
            }
        });

        // Même effet que l'autre mais le fait à chaque changement de la barre. 
        // Obligatoire à avoir sinon il y a des fois où les films ne sont pas retiré.
        $("#livesearch").change(function(){
            if ($("#livesearch").val() == "") {
                $("#dynamic_search").html("");
            }
        });

        // Quand on clique sur une carte (film), on utilise cette méthode pour cliquer étant donnée que
        // $(".card-clikc").click ne marche pas car les éléments utilisés sont dynamique.
        $(document).on ("click", ".card-click", function(){
            var filmId = $(this).data("id_film"); // récupère l'id du film cliqué

            // Switch permettenant de savoir quel film on choisi et le limiter à 5.
            // Si un film est ajoutée alors qu'il est déjà dans la sélection, il devient rouge (bordure)
            switch (movie_selection) {
                case 1:
                    $("#movie_select_1").html($(this).html()).addClass("card p-0 m-2");
                    $("#hidden_film_1").val(filmId); // remplit l'input caché
                    movie_selection = 2;
                    break;
            
                case 2:
                    if ( $(this).html() == $("#movie_select_1").html() ) {
                        $(this).addClass("border-4 border-danger")
                    } else {
                        $("#movie_select_2").html($(this).html()).addClass("card p-0 m-2");
                        $("#hidden_film_2").val(filmId); // remplit l'input caché
                        movie_selection = 3;
                    }
                    break;

                case 3:
                    if ( $(this).html() == $("#movie_select_1").html() || $(this).html() == $("#movie_select_2").html() ) {
                        $(this).addClass("border-4 border-danger")
                    } else {
                        $("#movie_select_3").html($(this).html()).addClass("card p-0 m-2");
                        $("#hidden_film_3").val(filmId); // remplit l'input caché
                        movie_selection = 4;
                    }
                    break;

                case 4:
                    if ( $(this).html() == $("#movie_select_1").html() || $(this).html() == $("#movie_select_2").html() || $(this).html() == $("#movie_select_3").html() ) {
                        $(this).addClass("border-4 border-danger")
                    } else {
                        $("#movie_select_4").html($(this).html()).addClass("card p-0 m-2");
                        $("#hidden_film_4").val(filmId); // remplit l'input caché
                        movie_selection = 5;
                    }
                    break;

                case 5:
                    if ( $(this).html() == $("#movie_select_1").html() || $(this).html() == $("#movie_select_2").html() || $(this).html() == $("#movie_select_3").html() || $(this).html() == $("#movie_select_4").html() ) {
                        $(this).addClass("border-4 border-danger")
                    } else {
                        $("#movie_select_5").html($(this).html()).addClass("card p-0 m-2");
                        $("#hidden_film_5").val(filmId); // remplit l'input caché
                        movie_selection = 6;
                    }
                    break;
            }
        });

        // Suppression en cliquant sur un slot film 
        $("#movie_select_1").click(function(){
            if ($(this).html() == "") return;
            $("#movie_select_1").html($("#movie_select_2").html());
            $("#hidden_film_1").val($("#hidden_film_2").val());
            $("#movie_select_2").html($("#movie_select_3").html());
            $("#hidden_film_2").val($("#hidden_film_3").val());
            $("#movie_select_3").html($("#movie_select_4").html());
            $("#hidden_film_3").val($("#hidden_film_4").val());
            $("#movie_select_4").html($("#movie_select_5").html());
            $("#hidden_film_4").val($("#hidden_film_5").val());
            $("#movie_select_5").html("").removeClass("card p-0 m-2");
            $("#hidden_film_5").val("");
            if (movie_selection > 1) movie_selection--;
        });

        $("#movie_select_2").click(function(){
            if ($(this).html() == "") return;
            $("#movie_select_2").html($("#movie_select_3").html());
            $("#hidden_film_2").val($("#hidden_film_3").val());
            $("#movie_select_3").html($("#movie_select_4").html());
            $("#hidden_film_3").val($("#hidden_film_4").val());
            $("#movie_select_4").html($("#movie_select_5").html());
            $("#hidden_film_4").val($("#hidden_film_5").val());
            $("#movie_select_5").html("").removeClass("card p-0 m-2");
            $("#hidden_film_5").val("");
            if (movie_selection > 2) movie_selection--;
        });

        $("#movie_select_3").click(function(){
            if ($(this).html() == "") return;
            $("#movie_select_3").html($("#movie_select_4").html());
            $("#hidden_film_3").val($("#hidden_film_4").val());
            $("#movie_select_4").html($("#movie_select_5").html());
            $("#hidden_film_4").val($("#hidden_film_5").val());
            $("#movie_select_5").html("").removeClass("card p-0 m-2");
            $("#hidden_film_5").val("");
            if (movie_selection > 3) movie_selection--;
        });

        $("#movie_select_4").click(function(){
            if ($(this).html() == "") return;
            $("#movie_select_4").html($("#movie_select_5").html());
            $("#hidden_film_4").val($("#hidden_film_5").val());
            $("#movie_select_5").html("").removeClass("card p-0 m-2");
            $("#hidden_film_5").val("");
            if (movie_selection > 4) movie_selection--;
        });

        $("#movie_select_5").click(function(){
            if ($(this).html() == "") return;
            $("#movie_select_5").html("").removeClass("card p-0 m-2");
            $("#hidden_film_5").val("");
            if (movie_selection > 5) movie_selection--;
        });

        // élément repris du TP JQuery et modifié pour PopCo: Permet d'ajouter 3 lieu à une soirée.
        $("#add_place").click(function() {
            if ($("#place").val() == "") {
                return;
            }
            if (place_selection <= 4) {
                $("button[name='party_confirm']").attr('disabled','true');
                var tache = $("#place").val();
                var nouvelItem = $("<li>",{text:tache}).addClass("list-group-item list-group-item-danger");
                nouvelItem.append('<input type="hidden" name="choixLieu'+place_selection+'" value="'+tache+'">');
                $("ol").append(nouvelItem);
                $("#place").val("").focus();
                place_selection++;
            } 
            if (place_selection == 4) {
                $("#add_place").attr('disabled','true');
                $("button[name='party_confirm']").removeAttr('disabled');
            } 
        });

        // Le boutton submit peut être appuyé que lorsque les 3 lieu sont ajoutés.
        $("#remove_place").click(function() {
            $("ol").empty();
            $("#add_place").removeAttr('disabled');
            $("button[name='party_confirm']").attr('disabled','true');
            place_selection = 1;
        });	

    });
</script>

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
                                <li class="nav-item">
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
                                    <a class="nav-link bootstrap_nav_item_color active" href="../public/soiree_create.php">Créer une soirée</a>
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
                                    <a href="./films.php" class="list-group-item list-group-item-action">
                                        Films
                                    </a>
                                    <?php if(isset($_SESSION['email'])) { ?>
                                    <a href="../public/soiree_create" class="list-group-item list-group-item-action active list-group-item-ctm-terciary-color-subtle">
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
        <div class="text-center my-5 py-5">
            <h5>Créez une nouvelle soirée !</h5>
        </div>
        <h5 class="ms-5">Entrez les informations de votre soirée</h5>

        <div class="container my-5">

            <form method="POST" action="" enctype="multipart/form-data">
                <!-- partie formulaire -->

                 <!-- Nom de la soirée -->
                <div class="mb-3">
                    <label for="nomSoiree" class="form-label">Nom de la soirée</label>
                    <input type="text" class="form-control" name="nomSoiree" id="nomSoiree" maxlength="30" placeholder="Soirée film d'horreur" required>
                </div>

                <div class="mb-3">
                    <label for="description_soiree" class="form-label">Description de la soirée</label>
                    <input type="text" class="form-control" placeholder="Entrez une description..." name="description_soiree" autocomplete="off" maxlength="500" required>
                </div>
    
                <!-- choix du genre de la soirée avec les propositions en formulaire de sélection -->
                <div class="mb-3">
                    <label for="genre_movie">Genre de la soirée</label>
                    <select class="form-select" aria-label="genre_movie" name="genre_movie" id="genre_movie">
                    <option value="Genre-Rien">Sans genre</option>
                    <option value="Horreur" >Horreur</option>
                    <option value="Science-Fiction" >Science Fiction</option>
                    <option value="Romance">Romance</option>
                    <option value="Fantastique">Fantastique</option>
                    <option value="action">Action</option>
                    <option value="Animation">Animation</option>
                    <option value="thriller">Thriller</option>
                    <option value="comédie">Comédie</option>
                    <option value="documentaire">Documentaire</option>
                    <option value="historique">Historique</option>
                    </select>
                </div>


                <!-- Partie sur la recherche des films -->
                <div class="mb-3">
                    <label for="formFile" class="form-label">Recherchez les films que vous voulez donner à choisir aux participants (5 maximum)</label>
                    <input class="form-control mb-5" id="livesearch" type="text" placeholder="Search" aria-label="Search"/>
                </div>

                <div id="dynamic_search" class="row mx-3">

                </div>

                <input type="hidden" name="choixFilm1" id="hidden_film_1" value="">
                <input type="hidden" name="choixFilm2" id="hidden_film_2" value="">
                <input type="hidden" name="choixFilm3" id="hidden_film_3" value="">
                <input type="hidden" name="choixFilm4" id="hidden_film_4" value="">
                <input type="hidden" name="choixFilm5" id="hidden_film_5" value="">

                <div class="row mt-4 d-flex justify-content-between">
                    <div id="movie_select_1" class="col-2"></div>
                    <div id="movie_select_2" class="col-2"></div>
                    <div id="movie_select_3" class="col-2"></div>
                    <div id="movie_select_4" class="col-2"></div>
                    <div id="movie_select_5" class="col-2"></div>
                </div>

                <div class="mb-3">
                    <label for="range4" class="form-label">Nombre de personnes maximales pouvant être invités</label>
                    <input type="range" class="form-range" min="2" max="100" value="50" id="range4" name="nb_personne_max">
                    <output for="range4" id="rangeValue" aria-hidden="true"></output>

                    <script>
                    // This is an example script, please modify as needed
                    const rangeInput = document.getElementById('range4');
                    const rangeOutput = document.getElementById('rangeValue');

                    // Set initial value
                    rangeOutput.textContent = rangeInput.value;

                    rangeInput.addEventListener('input', function() {
                        rangeOutput.textContent = this.value;
                    });
                    </script>
                </div>
                <!-- partie pour mettre les dates grâce à un calendrier -->
                <div class="mb-3">
                    <label for="date_debut" class="">Date de la soirée :</label>
                    <input type="datetime-local" id="date_debut" name="date_debut" value="2026-06-01T00:00" min="2026-06-01" max="2099-12-31" required />
                </div>

                <div class="mb-3">
                    <label for="date_fin" class="">Date de fin de la soirée :</label>
                    <input type="datetime-local" id="date_fin" name="date_fin" value="2026-06-01T00:00" min="2026-06-01" max="2099-12-31" required />
                </div>

                <div class="mb-3">
                    <label for="date_limite" class="">Date limite de vote :</label>
                    <input type="datetime-local" id="date_limite" name="date_limite" value="2026-06-01T00:00" min="2026-06-01" max="2099-12-31" required />
                </div>

                <div class="mb-3">
                    <label for="place" class="form-label">Choix de Lieu (3 Maxiumum)</label>
                    <input type="text" name="place" class="form-control mb-4" id="place" placeholder="7 Rue George Clemenceau">
                    
                    <button id="add_place" type="button" class="btn btn-primary">Ajouter</button>
                    <button id="remove_place" type="button" class="btn btn-primary">Supprimer</button>
                </div>

                <div class="container mt-4">
                    <ol id="liste" class="list-group list-group-numbered">
                    </ol>
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">Choisissez une image de fond pour votre soirée</label>
                    <div id="emailHelp" class="form-text mb-3">Cette image accompagnera la présentation de votre soirée, pour donner l'ambiance que vous voulez transmettre  Veuillez ne pas mettre d'image offensante (Png,Jpg,Jpeg,svg)</div>

                    <input class="form-control" type="file" id="formFile" name="formFile" accept=".png,.jpg,.jpeg,.svg" required>
                </div>

                <!-- bouton pour soumettre la soirée -->
                 <button name="party_confirm" type="submit" class="btn btn-primary" disabled>Submit</button>
            </form>

        </div>
    </main>
    <!-- Footer via un include afin de ne pas avoir de code répété  -->
    <footer id="footer_popco" class="container-fluid py-3 rounded-top-5 bg-ctm-primary-color">
        <?php include("../include_code/footer.php");?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>