<?php
$connect = mysqli_connect("127.0.0.1:3306","root","","popco_bdd");

if (!$connect) {
    echo "Failed to access the DB:" . mysqli_connect_error();
    exit();
}

?>