<!-- 
vote.php - Vote
Cette page permet de voter à propos d'une certaine soirée, l'utilsateur doit voter un lieu et une soirée.
-->

<?php
session_start();

require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();

// Vérifie si un utilisateur est connecté, sinon il est renvoyé à la page de connexion
if(!isset($_SESSION['email'])) {
    header('Location: ./connexion.php');
}

// Récupère les informations de l'utilisateur s'il est connecté
if(isset($_SESSION['email'])){
    $utilisateur_infos_requete = $bdd->prepare("SELECT * FROM utilisateur WHERE email=?");
    $utilisateur_infos_requete->execute(array($_SESSION['email']));
    $utilisateur_infos = $utilisateur_infos_requete->fetch();
}

// Vérifie que l'id de la soirée a bien été récupéré
if(isset($_GET['id_soiree']) AND !empty($_GET['id_soiree'])){
    // Récupère l'id de l'élément voulu depuis l'URL
    $id_soiree_get = $_GET['id_soiree'];

    // Récupération des informations de la soirée, des films et des lieux
    $soiree_infos_requete = $bdd->prepare('SELECT
    s.*,
    f1.id_film AS film1_id_film, f1.nom_film AS film1_nom_film, f1.affiche AS film1_affiche,
    f2.id_film AS film2_id_film, f2.nom_film AS film2_nom_film, f2.affiche AS film2_affiche,
    f3.id_film AS film3_id_film, f3.nom_film AS film3_nom_film, f3.affiche AS film3_affiche,
    f4.id_film AS film4_id_film, f4.nom_film AS film4_nom_film, f4.affiche AS film4_affiche,
    f5.id_film AS film5_id_film, f5.nom_film AS film5_nom_film, f5.affiche AS film5_affiche,
    l1.id_lieu AS lieu1_id, l1.adresse AS lieu1_adresse,
    l2.id_lieu AS lieu2_id, l2.adresse AS lieu2_adresse,
    l3.id_lieu AS lieu3_id, l3.adresse AS lieu3_adresse

    FROM soiree s
    JOIN film f1 ON s.choix_1_film = f1.id_film
    JOIN film f2 ON s.choix_2_film = f2.id_film
    JOIN film f3 ON s.choix_3_film = f3.id_film
    JOIN film f4 ON s.choix_4_film = f4.id_film
    JOIN film f5 ON s.choix_5_film = f5.id_film
    JOIN lieu l1 ON s.choix_1_lieu = l1.id_lieu
    JOIN lieu l2 ON s.choix_2_lieu = l2.id_lieu
    JOIN lieu l3 ON s.choix_3_lieu = l3.id_lieu

    WHERE s.id_soiree = ?;');
    
    $soiree_infos_requete->execute(array($id_soiree_get));

    // Si cette colonne existe, les données déjà éxistantes sont récupées
    if($soiree_infos_requete->rowCount() > 0){

        $soiree_infos = $soiree_infos_requete->fetch();
    }
    else{
        echo "soiree introuvable. Vous allez être redirigé vers les soirees.";
    }
}
else{
    echo "Une erreur s'est produite, l'identifiant n'est pas parvenu à être récupéré, veuillez revenir à la page précédente.";
}


function add_vote($vote_film, $vote_lieu, $id_soiree, $id_utilisateur) {
    global $bdd;

    if (!empty($vote_film) && !empty($vote_lieu)) {
        $creer = $bdd->prepare("INSERT INTO vote(id_soiree, id_utilisateur, choix_film, choix_lieu) VALUES(?, ?, ?, ?)");
        if ($creer->execute([$id_soiree, $id_utilisateur, $vote_film, $vote_lieu])) {
            header('Location: ./soiree_infos.php?id_soiree='.$id_soiree);
            exit();
        } else {
            echo "Erreur lors de l'enregistrement du vote.";
        }
    } else {
        echo "Veuillez compléter tous les champs.";
    }
}

if (isset($_POST['vote_complet'])) {
    add_vote(
        $_POST['vote_film'],
        $_POST['vote_lieu'],
        $id_soiree_get,
        $utilisateur_infos['id_utilisateur']
    );
}

if (isset($_POST['vote_complet'])) {
    add_vote($_POST['vote_film'], $_POST['vote_lieu']);
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
    <title>Voter ! </title>
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
                                    <a href="./index" class="list-group-item list-group-item-action active list-group-item-ctm-terciary-color-subtle" aria-current="true">
                                        Accueil
                                    </a>
                                    <a href="./soirees" class="list-group-item list-group-item-action">
                                        Soirées
                                    </a>
                                    <a href="./films.php" class="list-group-item list-group-item-action">
                                        Films
                                    </a>
                                    <?php if(isset($_SESSION['email'])) { ?>
                                    <a href="../public/soiree_create" class="list-group-item list-group-item-action">
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
        <div class="container my-5" id="formulaireVote">
        <h5 class="fs-3">Votez pour le film que vous aimeriez voir !</h5>
        <p>Pour la soirée <strong><?= $soiree_infos['nom_soiree'] ?></strong>, choisissez un film et un lieu.</p>

        <form method="POST" action="">

            <!-- FILMS -->
            <h5 class="mb-3">Film</h5>
            <div class="container d-flex justify-content-between flex-wrap gap-3">

                <div class="col-12 col-md-6 col-lg-2">
                    <input type="radio" required class="btn-check" id="btn-film-1" autocomplete="off" name="vote_film" value="<?= $soiree_infos['film1_id_film'] ?>">
                    <label class="btn btn-lg w-100" for="btn-film-1">
                        <figure>
                            <img src="<?= $soiree_infos['film1_affiche'] ?>" class="figure-img img-fluid object-fit-cover" alt="">
                            <figcaption><?= $soiree_infos['film1_nom_film'] ?></figcaption>
                        </figure>
                    </label>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <input type="radio" required class="btn-check" id="btn-film-2" autocomplete="off" name="vote_film" value="<?= $soiree_infos['film2_id_film'] ?>">
                    <label class="btn btn-lg w-100" for="btn-film-2">
                        <figure>
                            <img src="<?= $soiree_infos['film2_affiche'] ?>" class="figure-img img-fluid object-fit-cover" alt="">
                            <figcaption><?= $soiree_infos['film2_nom_film'] ?></figcaption>
                        </figure>
                    </label>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <input type="radio" required class="btn-check" id="btn-film-3" autocomplete="off" name="vote_film" value="<?= $soiree_infos['film3_id_film'] ?>">
                    <label class="btn btn-lg w-100" for="btn-film-3">
                        <figure>
                            <img src="<?= $soiree_infos['film3_affiche'] ?>" class="figure-img img-fluid object-fit-cover" alt="">
                            <figcaption><?= $soiree_infos['film3_nom_film'] ?></figcaption>
                        </figure>
                    </label>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <input type="radio" required class="btn-check" id="btn-film-4" autocomplete="off" name="vote_film" value="<?= $soiree_infos['film4_id_film'] ?>">
                    <label class="btn btn-lg w-100" for="btn-film-4">
                        <figure>
                            <img src="<?= $soiree_infos['film4_affiche'] ?>" class="figure-img img-fluid object-fit-cover" alt="">
                            <figcaption><?= $soiree_infos['film4_nom_film'] ?></figcaption>
                        </figure>
                    </label>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <input type="radio" required class="btn-check" id="btn-film-5" autocomplete="off" name="vote_film" value="<?= $soiree_infos['film5_id_film'] ?>">
                    <label class="btn btn-lg w-100" for="btn-film-5">
                        <figure>
                            <img src="<?= $soiree_infos['film5_affiche'] ?>" class="figure-img img-fluid object-fit-cover" alt="">
                            <figcaption><?= $soiree_infos['film5_nom_film'] ?></figcaption>
                        </figure>
                    </label>
                </div>

            </div>

            <!-- LIEUX -->
            <h5 class="mt-5 mb-3">Lieu</h5>
            <div class="container d-flex justify-content-start flex-wrap gap-3">

                <div class="col-12 col-md-4 col-lg-3">
                    <input type="radio" required class="btn-check" id="btn-lieu-1" autocomplete="off" name="vote_lieu" value="<?= $soiree_infos['lieu1_id'] ?>">
                    <label class="btn btn-outline-primary btn-lg w-100" for="btn-lieu-1">
                        <?= $soiree_infos['lieu1_adresse'] ?>
                    </label>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <input type="radio" required class="btn-check" id="btn-lieu-2" autocomplete="off" name="vote_lieu" value="<?= $soiree_infos['lieu2_id'] ?>">
                    <label class="btn btn-outline-primary btn-lg w-100" for="btn-lieu-2">
                        <?= $soiree_infos['lieu2_adresse'] ?>
                    </label>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <input type="radio" required class="btn-check" id="btn-lieu-3" autocomplete="off" name="vote_lieu" value="<?= $soiree_infos['lieu3_id'] ?>">
                    <label class="btn btn-outline-primary btn-lg w-100" for="btn-lieu-3">
                        <?= $soiree_infos['lieu3_adresse'] ?>
                    </label>
                </div>

            </div>

            <button type="submit" name="vote_complet" class="btn btn-ctm-red mt-4 w-100 p-3">Voter</button>

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