<!-- 
livesearch.php - Recherche d'un film 
Cette sous-page permet de faire une recherche en temps réelle de tout
les films de la base de donnée pour qu'un utilisateur puisse faire la 
recherche de film dessus pour les chosiie
-->

<?php
require_once '../bdd/bdd_connexion.php';
$bdd = connectBDS();
if(isset($_POST['result_search'])) {

    // l'asterix agit comme un % pour les LIKE mais pour les BOOLEAN MODE
    $result_get = $_POST['result_search'] . '*';
    $sql_command= "SELECT * FROM film WHERE MATCH(nom_film) AGAINST (? IN BOOLEAN MODE)";

    $query_db = $bdd->prepare($sql_command);
    $query_db->execute(array($result_get));

    if ($query_db->rowcount() > 0) {
    ?>
        <div id="scrollbar" class="col-12 overflow-x-scroll me-5">
            <div id ="img-resize" class="row row-cols-2 row-cols-md-5 ms-1 my-3 g-5 flex-nowrap gap-3">
                <?php
                while($fetchDB = $query_db->fetch()) {
                ?>
                <div class="card-click card p-0 m-0" data-id_film="<?= $fetchDB['id_film']?>">
                    <img src=<?= $fetchDB['affiche'];?> class="card-img-top object-fit-cover" alt="...">
                    <h5 class="card-text text-center"><?= $fetchDB['nom_film'];?></h5>
                </div>

                <?php } ?>
                
            </div>
        </div>
    <?php } ?>
<?php } ?>