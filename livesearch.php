<?php
include("db_infos.php");

if(isset($_POST['result_search'])) {

    $result_get = $_POST['result_search'];
    
    $sql_command= "SELECT * FROM film WHERE MATCH(nom_film) AGAINST (? IN NATURAL LANGUAGE MODE)";

    $exeCom = mysqli_prepare($connect,$sql_command);
    mysqli_stmt_bind_param($exeCom, 's', $result_get);
    mysqli_stmt_execute($exeCom);

    $query_db = mysqli_stmt_get_result($exeCom); 
    echo $sql_command;


    if ($query_db && mysqli_num_rows($query_db) > 0) {
    ?>
        <div id="scrollbar" class="col-12 overflow-x-scroll me-5">
            <div id ="img-resize" class="row row-cols-2 row-cols-md-5 ms-1 my-3 g-5 flex-nowrap gap-3">
                <?php
                foreach ($query_db as $key => $value) {
                    $movie_name = $value['nom_film'];  
                ?>
                <div class="card p-0 m-0">
                    <img src="./assets/images/rella_16th_birthday_edit.jpg" class="card-img-top object-fit-cover" alt="...">
                    <h5 class="card-text text-center"><?= $movie_name;?></h5>
                </div>

                <?php } ?>
                
            </div>
        </div>
    <?php } ?>
<?php mysqli_close($connect); } ?>