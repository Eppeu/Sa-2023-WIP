<?php
// session_start();
// if(!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != TRUE) header("Location: ../public/index.php");

include("../bdd/db_infos.php");

if(isset($_POST['result_search'])) {

    $result_get = $_POST['result_search'] . '*';
    
    $sql_command= "SELECT * FROM film WHERE MATCH(nom_film) AGAINST (? IN BOOLEAN MODE)";

    $exeCom = mysqli_prepare($connect,$sql_command);

    mysqli_stmt_bind_param($exeCom, 's', $result_get);
    mysqli_stmt_execute($exeCom);

    $query_db = mysqli_stmt_get_result($exeCom); 


    if ($query_db && mysqli_num_rows($query_db) > 0) {
    ?>
        <div id="scrollbar" class="col-12 overflow-x-scroll me-5">
            <div id ="img-resize" class="row row-cols-2 row-cols-md-5 ms-1 my-3 g-5 flex-nowrap gap-3">
                <?php
                while($fetchDB = mysqli_fetch_assoc($query_db)) {
                ?>
                <div class="card-click card p-0 m-0" data-id_film="<?= $fetchDB['id_film']?>">
                    <img src=<?= $fetchDB['affiche'];?> class="card-img-top object-fit-cover" alt="...">
                    <h5 class="card-text text-center"><?= $fetchDB['nom_film'];?></h5>
                </div>

                <?php } ?>
                
            </div>
        </div>
    <?php } ?>
<?php mysqli_close($connect); } ?>