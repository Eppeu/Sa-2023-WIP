<?php
require_once './bdd/bdd_connexion.php';
$bdd = connectBDS();

$allSoirees = $bdd->query('SELECT * FROM film');
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
    <title>Créer une soirée</title>
</head>

<body class="bg-ctm-terciary-color">
    <header>
        <!-- Header contenant le menu de navigation version pour écran normal et version pour écran réduit -->
        <div class="container-fluid p-0">
                <nav id="header_popco" class="navbar navbar-expand bg-ctm-primary-color rounded-bottom-5 ">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="./index.php">
                            <img src="./assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
                            <!--Insertion d'une icône du logo PopCo -->
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
                        <!-- les liens vers le pages dans le menu de navigation -->
                        <a class="fs-1 d-block d-md-none text-success" data-bs-toggle="offcanvas" href="#menu_phone" aria-controls="offcanvasExample">
                        <i class="bi bi-list link-ctm-terciary-color"></i>
                        </a>
                        <div class="offcanvas-md d-md-none offcanvas-end bg-ctm-terciary-color" tabindex="-1" id="menu_phone" aria-labelledby="menu_phoneLabel">
                            <!-- ajout de la class offcanvas pour créer le menu burger (sur la version réduite du site) -->
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
                                    <a href="./soiree_create.php" class="list-group-item list-group-item-action active list-group-item-ctm-terciary-color-subtle">
                                        Créer une soirée
                                    </a>
                                    <a href="./films.php" class="list-group-item list-group-item-action">
                                        Films proposés
                                    </a>
                                    <a href="./utilisateur.php" class="list-group-item list-group-item-action">
                                        Utilisateur
                                    </a>
                                    <a href="./vote.php" class="list-group-item list-group-item-action">
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
        <div class="text-center my-5 py-5">
            <h5>Créez une nouvelle soirée !</h5>
        </div>
        <h5 class="ms-5">Entrez les informations de votre soirée</h5>

        <div class="container my-5">

            <form>
                <!-- partie formulaire -->
                <div class="mb-3">
                    <label for="" class="form-label">Nom de la soirée</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" maxlength="30" placeholder="Soirée film d'horreur">
                </div>

                <script>
                $(document).ready(function(){

                })
                </script>

            <div id="dynamic_search" class="row mx-3">
                <div id="scrollbar" class="col-12 overflow-x-scroll me-5">
                    <div id ="img-resize" class="row row-cols-2 row-cols-md-5 ms-1 my-3 g-5 flex-nowrap gap-3">
                        <?php
                        while($allSoireesInfos = $allSoirees->fetch()){
                        ?>
                            <div class="card p-0 m-0">
                                <img src="./assets/images/yuri_time.png" class="card-img-top object-fit-cover" alt="...">
                                <h5 class="card-text text-center align-text-bottom"><?= $allSoireesInfos['nom_film'];?></h5>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="mb-3">
                    <!-- choix du genre de la soirée avec les propositions en formulaire de sélection -->
                    <label for="genre_movie">Genre de la soirée</label>
                    <select class="form-select" aria-label="genre_movie">
                    <option selected>Choisissez un genre de soirée</option>
                    <option value="">Sans genre</option>
                    <option value="horror">Horreur</option>
                    <option value="sf">Science Fiction</option>
                    <option value="Romance">Romance</option>
                    <option value="fantasy">Fantastique</option>
                    <option value="action">Action</option>
                    <option value="Animation">Animation</option>
                    <option value="thriller">Thriller</option>
                    <option value="comedie">Comédie</option>
                    <option value="doc">Documentaire</option>
                    <option value="historique">Historique</option>
                    </select>
                </div>


                <div class="mb-3">
                    <!-- Partie sur la recherche des films -->
                    <label for="formFile" class="form-label">Recherchez les films que vous voulez donner à choisir aux participants</label>
                    <input class="form-control mb-5" type="search" placeholder="Search" aria-label="Search"/>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </div>

                <div class="mb-3">
                    <label for="range4" class="form-label mt-3">Nombre de personnes à inviter</label>
                    <div id="" class="form-text mb-3">Indiquez le nombre de personnes maximum de la soirée</div>
                    <input type="range" class="form-range" min="0" max="100" value="5" id="range4">
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
                    <label for="start" class="">Date de la soirée :</label>
                    <input type="datetime-local" id="start" name="trip-start" value="2026-06-01" min="2026-06-01" max="2099-12-31" />
                </div>

                <div class="mb-3">
                    <label for="start" class="">Date de de fin de la soirée :</label>
                    <input type="datetime-local" id="start" name="trip-start" value="2026-06-01" min="2026-06-01" max="2099-12-31" />
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Propositions de lieux où la soirée va se dérouler</label>
                    <input type="password" class="form-control" id="">
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">Choisissez une image de fond pour votre soirée</label>
                    <div id="emailHelp" class="form-text mb-3">Cette image accompagnera la présentation de votre soirée, pour donner l'ambiance que vous voulez transmettre  Veuillez ne pas mettre d'image offensante</div>
                    <input class="form-control" type="file" id="formFile">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <!-- bouton pour soumettre la soirée -->
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
                <!-- image du logo -->
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
                            <!-- bouton pour fermer -->
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>