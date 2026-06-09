<?php
session_start();
if (isset($_POST['valider'])) {
    if (!empty($_POST['identifiant']) and !empty($_POST['mdp'])) {
        require_once './bdd/bdd_connexion.php';

        $id = htmlspecialchars($_POST['identifiant']);
        $mdp = htmlspecialchars($_POST['mdp']);
        $captcha = htmlspecialchars($_POST['captcha']);

        $bdd = connectBDS(); // Connexion à la BDD

        // Vérifie si le captcha fonctionne
        if ($_SESSION['captcha'] === $captcha) {
            // Récupération du mot de passe haché de l'utilisateur depuis la base de données
            $query = $bdd->prepare("SELECT mdpUser FROM utilisateur WHERE nomUser=:idSaisi");
            $query->bindParam(':idSaisi', $id);
            $query->execute();
            // Vérification du mot de passe
            if ($row = $query->fetch()) {
                $hashedMdp = $row['mdpUser'];
                if (password_verify($mdp, $hashedMdp)) {
                    $query2 = $bdd->prepare("SELECT nomUser, roleUser FROM utilisateur WHERE nomUser=:idSaisi");
                    $query2->bindParam(':idSaisi', $id);
                    $query2->execute();
                    $info = $query2->fetch();

                    $nomUser = $info['nomUser'];
                    $role = $info['roleUser'];

                    // session_start();
                    $_SESSION['nomUser'] = $nomUser;
                    $_SESSION['roleUser'] = $role;

                    echo $nomUser;
                    echo '<br>';
                    echo $role;

                    header('Location: ../ACCUEIL/VIDEO/videos.php');
                } else {
                    echo "
                    <div class='modal'>
                        <p>Identifiant ou mot de passe invalide</p>
                    </div>";
                }
            } else {
                echo "
                <div class='modal'>
                    <p>Identifiant ou mot de passe invalide</p>
                </div>";
            }
        } else {
            echo "
            <div class='modal'>
                <p>Captcha invalide</p>
            </div>";
        }
    } else {
        echo "Veuillez compléter tous les champs.";
    }
}

$characts = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$characts .= '123456789';
$code_aleatoire = '';
for ($i = 0; $i < 4; $i++) {
    $code_aleatoire .= substr($characts, rand() % (strlen($characts)), 1);
}

$_SESSION['captcha'] = $code_aleatoire;
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
    <title>PopCo</title>
</head>

<body class="bg-ctm-terciary-color">
    <header>
        <div class="container-fluid p-0">
                <nav id="header_popco" class="navbar navbar-expand bg-ctm-primary-color rounded-bottom-5 ">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="./index.php">
                            <img src="./assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
                        </a>
                        <div class="collapse navbar-collapse justify-content-between">
                            <ul class="navbar-nav mb-2 mb-lg-0 d-none d-md-flex">
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

                        <a class="fs-1 d-block d-md-none text-success" data-bs-toggle="offcanvas" href="#menu_phone" aria-controls="offcanvasExample">
                        <i class="bi bi-list link-ctm-terciary-color"></i>
                        </a>
                        <div class="offcanvas-md d-md-none offcanvas-end bg-ctm-terciary-color" tabindex="-1" id="menu_phone" aria-labelledby="menu_phoneLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="menu_phoneLabel">PopCo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#menu_phone" aria-label="Close"></button>
                            </div>
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

                                <div class="container-fluid d-md-flex justify-content-end gap-2">
                                    <a class="btn btn-ctm-red-subtle" href="./connexion.php">Se connecter</a>
                                    <a class="btn btn-ctm-red" href="./new_account.php">Créer un compte</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </nav>
        </div>
    </header>

    <main>
        <div class="text-center my-5 py-5">
            <h5>Se connecter</h5>
        </div>
        <div class="container my-5">

            <form>
                <div class="mb-3">
                    <label for="" class="form-label">Utilisateur</label>
                    <input type="text" class="form-control" id="login" aria-describedby="name" maxlength="30" placeholder="Utilisateur">
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Mot de Passe</label>
                    <input type="text" class="form-control" id="password" aria-describedby="name" maxlength="30" placeholder="Password">
                </div>

                <button type="submit" class="btn btn-ctm-red container-fluid p-3">Se connecter</button>
            </form>

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
            <div class="col-4 text-center">
                <img src="./assets/icons/PopCo_logo.png" alt="Logo PopCo - Accueil" width="80" height="80">
            </div>
            <div class="col-4 py-3 text-start d-lg-block text-end pe-4">
                <a class="text-decoration-none link-ctm-terciary-color-subtle" data-bs-toggle="modal" href="#popco_ml" role="button">
                Mentions légales
                </a>
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