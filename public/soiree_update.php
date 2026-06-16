<?php
session_start();
// Si l'utilisateur n'est pas connecté, il est renvoyé à la page de connexion
if(!$_SESSION['email']) {
    header('Location: ./connexion.php');
}

require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();

$allSoirees = $bdd->query('SELECT * FROM film');

if (isset($_POST['update_party'])) {
    $UPDATE_soiree_id = $_POST['SoireeID'];
    $UPDATE_soiree_name = $_POST['NameSoiree'];
    $UPDATE_soiree_description = $_POST['DescriptionSoiree'];
    $UPDATE_soiree_genre = $_POST['GenreSoiree'];
    $UPDATE_soiree_nmbMax = $_POST['NumberSoiree'];
    $UPDATE_soiree_dateS = $_POST['DateStartSoiree'];
    $UPDATE_soiree_dateE = $_POST['DateEndSoiree'];
    $UPDATE_soiree_lieu1 = $_POST['ChoixLieu1'];
    $UPDATE_soiree_lieu2 = $_POST['ChoixLieu2'];
    $UPDATE_soiree_lieu3 = $_POST['ChoixLieu3'];
    $UPDATE_soiree_image = $_POST['ImageSoire'];
}



function update($nomSoireeUPDATE, $descriptionSoireeUPDATE, $genreSoireeUPDATE, $choixLieu1UPDATE, $choixLieu2UPDATE, $choixLieu3UPDATE, $nb_personne_maxUPDATE, $date_debutUPDATE, $date_finUPDATE, $UPDATE_soiree_id) {

    global $bdd;

    $nomSoiree= nl2br(htmlspecialchars($nomSoireeUPDATE));
    $descriptionSoiree = nl2br(htmlspecialchars($descriptionSoireeUPDATE));
    $genreSoiree = nl2br(htmlspecialchars($genreSoireeUPDATE));

    $date_debut = str_replace('T', ' ', $date_debutUPDATE);
    $date_fin = str_replace('T', ' ', $date_finUPDATE);

    $time= date('YmdHis');
    $filename = $time . basename($_FILES["formFile"]["name"]);

    if ($date_debut > $date_fin) {
        echo 'La date de début de soirée est supérieur à la date de fin.';
    }
    
    if (!empty($nomSoiree) && !empty($descriptionSoiree) && 
        !empty($nb_personne_maxUPDATE) && !empty($date_debut) && !empty($date_fin) &&
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

        $UPDATESoiree = $bdd->prepare("UPDATE soiree SET nom_soiree = ?, description_soiree = ?, nb_personne_max = ?, genre_soiree = ?, date_debut = ?, date_fin = ?, choix_1_lieu = ?, choix_2_lieu = ?, choix_3_lieu = ?, image_soiree = ? WHERE id_soiree = ?");

        $UPDATESoiree->execute(Array($nomSoiree, $descriptionSoiree, $nb_personne_maxUPDATE, $genreSoiree, $date_debut, $date_fin, $id_lieu_1, $id_lieu_2, $id_lieu_3, $image_path, $UPDATE_soiree_id));

        header('Location: ./soiree_infos.php?id_soiree='.$UPDATE_soiree_id);
        exit();

    } else {
        echo "Veuillez compléter tous les champs.";
    }
}

if (isset($_POST["update_party_confirm"])) {
    update($_POST['nomSoiree'], $_POST['description_soiree'], $_POST['genre_movie'], $_POST['choixLieu1'], $_POST['choixLieu2'], $_POST['choixLieu3'], $_POST['nb_personne_max'], $_POST['date_debut'], $_POST['date_fin'], $_POST['soireeID']);
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
                                
                                <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']==TRUE) { ?>
                                <li class="nav-item">
                                    <a class="nav-link bootstrap_nav_item_color" href="./new_film.php">Ajouter un film</a>
                                    <!-- lien de navigation -->
                                </li>
                                <?php } ?>
                            </ul>

                            <?php
                            if(isset($_SESSION['email'])) {
                                ?>
                                <ul class="navbar-nav mb-2 mb-lg-0 gap-2 me-0 d-none d-md-flex">
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red-subtle" href="./utilisateur.php">Votre profil</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-ctm-red" href="../private/deconnexion.php">Se déconnecter</a>
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
                    <input type="range" class="form-range" min="0" max="100" value="<?= $UPDATE_soiree_nmbMax; ?>" id="range4" name="nb_personne_max">
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
                    <label for="place" class="form-label">Choix de Lieu (3 Maxiumum)</label>
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