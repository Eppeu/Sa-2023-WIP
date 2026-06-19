<!-- 
Index.php - Page d'acceuil 
Cette page est la page principale du site PopCo, elle permet de voir les dernières soirées créées
mais aussi les soirées populaire, c'est de ici que les utilisateurs peuvent acceder aux autres pages
et à la connexion.
-->

<?php
include('../include_code/connect_db.php');

// Sélection de tous les soirées (films) - Populaire et toute les 10 dernière soirées
$all_soirees = $bdd->query('SELECT *,LEFT(description_soiree, 200) FROM soiree WHERE date_fin > NOW() ORDER BY id_soiree DESC LIMIT 10;');
$soirees_populaire = $bdd->query("SELECT soiree.* FROM vote JOIN soiree ON vote.id_soiree  = soiree.id_soiree WHERE soiree.date_fin > NOW() GROUP BY soiree.id_soiree ORDER BY COUNT(vote.choix_film) DESC LIMIT 10;");


// Comptage du nombre de soirées
$count_soirees = $bdd->prepare('SELECT * FROM soiree');
$count_soirees->execute();
$count = $count_soirees->rowCount();

// Cette fonction permet de générer un bloc de code assez conséquent, il englobe une ligne, un slider et
// les différentes cartes de la soirée avec la requête SQL définie plus haut.
function generateCard($DBData) {
    echo 
    '<div class="row mx-3">
        <div id="scrollbar" class="col-12 overflow-x-scroll me-5">
            <div id ="img-resize" class="row row-cols-2 row-cols-md-5 ms-1 my-3 g-5 flex-nowrap gap-3">';
                while($DBInfo = $DBData->fetch()){
                    echo '<div class="card p-0 m-0">
                        <img src=' . $DBInfo["image_soiree"] . ' class="replace-img card-img-top object-fit-cover" alt="...">

                        <div class="card-body bg-ctm-primary-color-subtle">
                            <h5 class="card-title">' . $DBInfo["nom_soiree"] . '</h5>
                            <p class="card-text lh-1">' . $DBInfo["description_soiree"] . '...<p>
                        </div>
                        <div class="card-footer p-0 border-0">
                            <a href="./soiree_infos.php?id_soiree=' . $DBInfo["id_soiree"] . '"' . 'class="btn btn-ctm-red py-3 w-100 rounded-0 rounded-bottom-1">En savoir plus</a>
                        </div>
                    </div>';
                }
        echo '</div>
        </div>
    </div>';
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/icons/PopCo_favicon.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/style.css">
    <!-- Font Awesome pour les icônes -->
    <script src="https://kit.fontawesome.com/4b69bc6b92.js" crossorigin="anonymous"></script>
    <!-- Bootstrap Icons  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>PopCo - Accueil</title>

    <!-- 
    Script qui permet de changer les images à la condition où l'image originale prévue pour 
    la balise a une erreur.
    -->
    <script>
        $(document).ready(function(){
            $(".replace-img").on('error', function() {
                $(this).attr("src", "../assets/images/yuri_time.png");
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

    <main class="container-fluid px-0">
        
        <!-- 
        Si l'utilisateur se connecte, il n'y a pas d'utilité de montrer la partie 
        d'introduction du site, elle est présente uniquement quand l'utilisateur n'est
        pas connecter au site.
        -->
        <?php if(!isset($_SESSION['email'])) { ?>
            <div class="bgImage1"></div>
            <div class="text-center py-5 callToAction">
                <h5 class="fs-1">Organisez des soirées inoubliables !!</h5>
                <p class="px-3 fs-3 sticker bg-ctm-red">Déjà <?php echo $count; ?> soirées créées !</p>

                <p class="mt-5 fs-4">
                    Grâce à PopCo, trouvez des soirées où regarder de bons films ! <br/>
                    Rien de plus simple : cherchez une soirée, connectez-vous, votez le film qui vous intéresse le plus,<br/>
                    et le film qui remporte le plus de voix sera diffusé !<br/>
                </p>
            </div>
        <?php } ?>
        <!-- texte de présentation du site PopCo -->
        <div class="mainPart py-5 z-0">
            <!-- Utilisation de la fonction créée précédement afin d'afficher les cartes, uniquement le h5 n'est pas englobée
            étant donné qu'elle a des classes différentes et un texte différent aussi. -->
            <h5 class="ms-5 fs-3 text-ctm-primary-color-subtle">Les soirées récemment ajoutées !</h5>
            <?php generateCard($all_soirees); ?>
            <h5 class="ms-5 mt-4 fs-3">Les soirées populaires</h5>
            <?php generateCard($soirees_populaire); ?>
        </div>
    </main>

    <!-- Footer via un include afin de ne pas avoir de code répété  -->
    <footer id="footer_popco" class="container-fluid py-3 rounded-top-5 bg-ctm-primary-color">
        <?php include("../include_code/footer.php");?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>