<?php
// Requis pour avoir les informations de connexion
require_once('bdd_log_infos.php');

function connectBDS(){
try{
    // Connexion à la BDD
    $bdd = new PDO('mysql:host='. DBS_HOST .'; dbname='. DBS_BASE .';charset=utf8', DBS_USER, DBS_PASS);
    return $bdd;
    }

    catch (Exception $err){
    echo "Exception reçue : ", $err->getMessage(), "\n";
    return null;
    }
}

function requeteBDD(string $query, array $array, bool $result){
    $bdd = connectBDS();
    $stmt = $bdd->prepare($query);
    
    $stmt->execute($array);
    
    if($result){
        return $stmt->fetchAll();
    }
}
?>