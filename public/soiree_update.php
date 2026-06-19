<!-- 
soiree_update.php - Modification d'une soirée
Cette page permet au créateur d'une soirée de la modifier afin de régler des problèmes qui 
pourrait avoir. Seul l'auteur de la soirée peut la modifier et il n'est pas possible de
modifier un film après création d'une soirée.
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

if (isset($_POST['update_party'])) {
    $UPDATE_soiree_id = $_POST['SoireeID'];
    $UPDATE_soiree_name = $_POST['NameSoiree'];
    $UPDATE_soiree_description = $_POST['DescriptionSoiree'];
    $UPDATE_soiree_genre = $_POST['GenreSoiree'];
    $UPDATE_soiree_nmbMax = $_POST['NumberSoiree'];
    $UPDATE_soiree_dateS = $_POST['DateStartSoiree'];
    $UPDATE_soiree_dateE = $_POST['DateEndSoiree'];
    $UPDATE_soiree_dateVL = $_POST['DateLimiteVoteSoiree'];
    $UPDATE_soiree_lieu1 = $_POST['ChoixLieu1'];
    $UPDATE_soiree_lieu2 = $_POST['ChoixLieu2'];
    $UPDATE_soiree_lieu3 = $_POST['ChoixLieu3']; 
    $UPDATE_soiree_image = $_POST['ImageSoire'];
}



function update($nomSoireeUPDATE, $descriptionSoireeUPDATE, $genreSoireeUPDATE, $choixLieu1UPDATE, $choixLieu2UPDATE, $choixLieu3UPDATE, $nb_personne_maxUPDATE, $date_debutUPDATE, $date_finUPDATE, $date_limite_vote_UPDATE, $UPDATE_soiree_id) {

    global $bdd;

    $nomSoiree= nl2br(htmlspecialchars($nomSoireeUPDATE));
    $descriptionSoiree = nl2br(htmlspecialchars($descriptionSoireeUPDATE));
    $genreSoiree = nl2br(htmlspecialchars($genreSoireeUPDATE));

    $date_debut = str_replace('T', ' ', $date_debutUPDATE);
    $date_fin = str_replace('T', ' ', $date_finUPDATE);
    $date_vote_limite = str_replace('T', ' ', $date_limite_vote_UPDATE);

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
    
    if (!empty($nomSoiree) && !empty($descriptionSoiree) && 
        !empty($nb_personne_maxUPDATE) && !empty($date_debut) && !empty($date_fin) && !empty($date_vote_limite) &&
        !empty($choixLieu1UPDATE) && !empty($choixLieu2UPDATE) && !empty($choixLieu3UPDATE))
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
        $ajout_lieu_1->execute([$choixLieu1UPDATE]);
        $id_lieu_1 = $bdd->lastInsertId();

        // Ajout du deuxième lieu à la BDD
        $ajout_lieu_2 = $bdd->prepare("INSERT INTO lieu(adresse) VALUES(?)");
        $ajout_lieu_2->execute([$choixLieu2UPDATE]);
        $id_lieu_2 = $bdd->lastInsertId();

        // Ajout du deuxième lieu à la BDD
        $ajout_lieu_3 = $bdd->prepare("INSERT INTO lieu(adresse) VALUES(?)");
        $ajout_lieu_3->execute([$choixLieu3UPDATE]);
        $id_lieu_3 = $bdd->lastInsertId();

        // Récupère les informations de l'utilisateur connecté
        $utilisateur_infos_requete = $bdd->prepare("SELECT * FROM utilisateur WHERE email=?");
        $utilisateur_infos_requete->execute(array($_SESSION['email']));
        $utilisateur_infos = $utilisateur_infos_requete->fetch();

        $UPDATESoiree = $bdd->prepare("UPDATE soiree SET nom_soiree = ?, description_soiree = ?, nb_personne_max = ?, genre_soiree = ?, date_debut = ?, date_fin = ?, date_limite_vote = ?, choix_1_lieu = ?, choix_2_lieu = ?, choix_3_lieu = ?, image_soiree = ? WHERE id_soiree = ?");

        $UPDATESoiree->execute(Array($nomSoiree, $descriptionSoiree, $nb_personne_maxUPDATE, $genreSoiree, $date_debut, $date_fin, $date_vote_limite, $id_lieu_1, $id_lieu_2, $id_lieu_3, $image_path, $UPDATE_soiree_id));

        header('Location: ./soiree_infos.php?id_soiree='.$UPDATE_soiree_id);
        exit();

    } else {
        echo "Veuillez compléter tous les champs.";
    }
}

if (isset($_POST["update_party_confirm"])) {
    update($_POST['nomSoiree'], $_POST['description_soiree'], $_POST['genre_movie'], $_POST['choixLieu1'], $_POST['choixLieu2'], $_POST['choixLieu3'], $_POST['nb_personne_max'], $_POST['date_debut'], $_POST['date_fin'], $_POST['date_limite'], $_POST['soireeID']);
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
    let place_selection = 4;
     $("#add_place").attr('disabled','true');
     $("button[name='update_party_confirm']").removeAttr('disabled');
    
        $("#add_place").click(function() {
            if ($("#place").val() == "") {
                return;
            }
            if (place_selection <= 4) {
                $("button[name='update_party_confirm']").attr('disabled','true');
                var tache = $("#place").val();
                var nouvelItem = $("<li>",{text:tache}).addClass("list-group-item list-group-item-danger");
                nouvelItem.append('<input type="hidden" name="choixLieu'+place_selection+'" value="'+tache+'">');
                $("ol").append(nouvelItem);
                $("#place").val("").focus();
                place_selection++;
            } 
            if (place_selection == 4) {
                $("#add_place").attr('disabled','true');
                $("button[name='update_party_confirm']").removeAttr('disabled');
            } 
        });
        $("#remove_place").click(function() {
            $("ol").empty();
            $("#add_place").removeAttr('disabled');
            $("button[name='update_party_confirm']").attr('disabled','true');
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
        <div class="text-center my-5 py-5">
            <h5><?= $UPDATE_soiree_name; ?></h5>
        </div>
        <h5 class="ms-5">Modifier les éléments de cette soirée</h5>

        <div class="container my-5">

            <form method="POST" action="" enctype="multipart/form-data">
                <!-- partie formulaire -->

                 <!-- Nom de la soirée -->
                <div class="mb-3">
                    <label for="nomSoiree" class="form-label">Nom de la soirée</label>
                    <input type="text" class="form-control" name="nomSoiree" id="nomSoiree" maxlength="30" placeholder="Soirée film d'horreur" value="<?= $UPDATE_soiree_name; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description_soiree" class="form-label">Description de la soirée</label>
                    <input type="text" class="form-control" placeholder="Entrez une description..." name="description_soiree" autocomplete="off" maxlength="3000" value="<?= $UPDATE_soiree_description; ?>" required>
                </div>
    
                <!-- choix du genre de la soirée avec les propositions en formulaire de sélection -->
                <div class="mb-3">
                    <label for="genre_movie">Genre de la soirée</label>
                    <select class="form-select" aria-label="genre_movie" name="genre_movie" id="genre_movie">
                    <option selected value="<?= $UPDATE_soiree_genre; ?>"><?= $UPDATE_soiree_genre; ?></option>
                    <option value="">Sans genre</option>
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

                <div class="mb-3">
                    <label for="range4" class="form-label">Nombre de personnes maximales pouvant être invités</label>
                    <input type="range" class="form-range" min="2" max="100" value="<?= $UPDATE_soiree_nmbMax; ?>" id="range4" name="nb_personne_max">
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
                    <label for="date_fin">Date de début de  soirée :</label>
                    <input type="datetime-local" id="date_debut" name="date_debut" min="2026-06-01" max="2099-12-31" value= "<?= $UPDATE_soiree_dateS; ?>" required />
                </div>

                <div class="mb-3">
                    <label for="date_fin">Date de fin de la soirée :</label>
                    <input type="datetime-local" id="date_fin" name="date_fin" min="2026-06-01" max="2099-12-31" value= "<?= $UPDATE_soiree_dateE; ?>" required />
                </div>

                <div class="mb-3">
                    <label for="date_fin">Date limite de vote de la soirée :</label>
                    <input type="datetime-local" id="date_limite" name="date_limite" min="2026-06-01" max="2099-12-31" value= "<?= $UPDATE_soiree_dateVL; ?>" required />
                </div>

                <div class="mb-3">
                    <label for="place" class="form-label">Choix de Lieu (3 lieu)</label>
                    <input type="text" name="place" class="form-control mb-4" id="place" placeholder="7 Rue George Clemenceau">
                    
                    <button id="add_place" type="button" class="btn btn-primary">Ajouter</button>
                    <button id="remove_place" type="button" class="btn btn-primary">Supprimer</button>
                </div>

                <div class="container mt-4">
                    <ol id="liste" class="list-group list-group-numbered">
                        <li class="list-group-item list-group-item-danger">
                            <?= $UPDATE_soiree_lieu1; ?>
                            <input type="hidden" name="choixLieu1" value="<?= $UPDATE_soiree_lieu1; ?>">
                        </li>
                        <li class="list-group-item list-group-item-danger">
                            <?= $UPDATE_soiree_lieu2; ?>
                            <input type="hidden" name="choixLieu2" value="<?= $UPDATE_soiree_lieu2; ?>">
                        </li>
                        <li class="list-group-item list-group-item-danger">
                            <?= $UPDATE_soiree_lieu3; ?>
                            <input type="hidden" name="choixLieu3" value="<?= $UPDATE_soiree_lieu3; ?>">
                        </li>
                    </ol>
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">Choisissez une image de fond pour votre soirée</label>
                    <div id="emailHelp" class="form-text mb-3">Cette image accompagnera la présentation de votre soirée, pour donner l'ambiance que vous voulez transmettre  Veuillez ne pas mettre d'image offensante</div>

                    <input class="form-control" type="file" id="formFile" name="formFile" accept=".png,.jpg,.jpeg,.svg" value ="<?= $UPDATE_soiree_image; ?>" required>
                </div>

                <input hidden name="soireeID" id="soireeID" value="<?= $UPDATE_soiree_id ?>">
                <button name="update_party_confirm" type="submit" class="btn btn-primary" disabled>Submit</button>
                <!-- bouton pour soumettre la soirée -->
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