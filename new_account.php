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
    <title>Créer un compte</title>
</head>

<body class="bg-ctm-terciary-color">
    <header>
        <div class="container-fluid p-0">
                <nav id="header_popco" class="navbar navbar-expand bg-ctm-primary-color rounded-bottom-5 ">
                    <!-- création de barre de navigation -->
                    <div class="container-fluid">
                        <a class="navbar-brand" href="./index.php">
                            <img src="./assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
                        </a>
                        <!-- insertion du logo menant vers la page d'accueil -->
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
                            <!-- lien dans la barre de navigation menant au autre pages -->
                            <ul class="navbar-nav mb-2 mb-lg-0 gap-2 me-0 d-none d-md-flex">
                                <li class="nav-item">
                                    <a class="btn btn-ctm-red-subtle" href="./connexion.php">Se connecter</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-ctm-red" href="./new_account.php">Créer un compte</a>
                                </li>
                            </ul>
                            <!-- bouton de connexion et de création de compte -->
                        </div>

                        <a class="fs-1 d-block d-md-none text-success" data-bs-toggle="offcanvas" href="#menu_phone" aria-controls="offcanvasExample">
                        <i class="bi bi-list link-ctm-terciary-color"></i>
                        </a>
                        <div class="offcanvas-md d-md-none offcanvas-end bg-ctm-terciary-color" tabindex="-1" id="menu_phone" aria-labelledby="menu_phoneLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="menu_phoneLabel">PopCo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#menu_phone" aria-label="Close"></button>
                            </div>
                            <!-- affichage de téléphone avec menu burger -->
                            <div class="offcanvas-body d-flex flex-column justify-content-between px-0">
                                <ul class="list-group">
                                    <a href="./index.php" class="list-group-item list-group-item-action active list-group-item-ctm-terciary-color-subtle" aria-current="true">
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
                                    <a href="./vote.php" class="list-group-item list-group-item-action">
                                        Voter
                                    </a>
                                </ul>
                                <!-- lien vers les autres pages affichage de téléphone -->
                                <div class="container-fluid d-md-flex justify-content-end gap-2">
                                    <a class="btn btn-ctm-red-subtle" href="./connexion.php">Se connecter</a>
                                    <a class="btn btn-ctm-red" href="./new_account.php">Créer un compte</a>
                                </div>
                                <!-- bouton de connexion et de création de compte de téléphone -->
                            </div>
                        </div>

                    </div>
                </nav>
        </div>
    </header>

    <main>
        <div class="text-center my-5 py-5">
            <h5>Créer un compte</h5>
        </div>
        <div class="container my-5">

            <form method="POST" action="./utilisateur_create.php" class="form">
                <!-- partie formulaire -->
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" name="nom" maxlength="30" placeholder="Doe">
                </div>

                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" name="prenom" maxlength="30" placeholder="John">
                </div>

                <div class="mb-3">
                    <label for="e-mail" class="form-label">E-mail</label>
                    <input type="text" class="form-control" name="e-mail" maxlength="30" placeholder="E-mail">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de Passe</label>
                    <input type="password" class="form-control" name="password" maxlength="30" placeholder="Password">
                </div>

                <div class="mb-3">
                    <label for="passwordConfirm" class="form-label">Confirmer le Mot de Passe</label>
                    <input type="password" class="form-control" name="passwordConfirm" maxlength="30" placeholder="Password">
                </div>

                <button type="submit" class="btn btn-ctm-red container-fluid p-3" name="submit">Créer le compte</button>
                <!-- bouton de type submit pour la création du compte -->
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
            <!-- icone lien vers les réseaux sociaux -->
            <div class="col-4 text-center">
                <img src="./assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
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
</html>