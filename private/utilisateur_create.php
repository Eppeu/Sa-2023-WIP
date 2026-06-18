<?php
session_start();

include("./user_info.php");

// Si le client n'est pas connecté et n'est pas un utilisateur admin, il est redirigé à la page d'accueil
if(!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != TRUE) header("Location: ../public/index.php");

require_once '../bdd/bdd_connexion.php';

function add($ligne1, $ligne2, $ligne3, $ligne4, $ligne5){
    $bdd = connectBDS();

    $info1 = nl2br(htmlspecialchars($ligne1));
    $info2 = nl2br(htmlspecialchars($ligne2));
    $info3 = nl2br(htmlspecialchars($ligne3));
    $info4 = nl2br(htmlspecialchars($ligne4));
    $info5 = nl2br(htmlspecialchars($ligne4));

    if(!empty($info1) AND !empty($info2) AND !empty($info3) AND !empty($info4) AND !empty($info5)){
        if ($info4 == $info5){
            $password = password_hash($info4, PASSWORD_DEFAULT);

            $creer = $bdd->prepare("INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, email, mot_de_passe, is_admin)VALUES(?, ?, ?, ?, ?)");
            $creer->execute(array($info1, $info2, $info3, $password, FALSE));
            $user_ip = $_SERVER['REMOTE_ADDR'];
            setcookie("account_created", "true", time() + (86400 * 30), "/");
            header('Location: ../public/connexion.php');
        }else{
            echo "Mot de passe incorrect";
            header('Location: ../public/compte_create.php');
        }
    }else{
        echo `HOOO Veuillez compléter tous les champs. info 1 : $info1, info 2 : $info2, info 3 : $info3, info 4 : $info4, info 5 : $info5, `;
    }
}

if(isset($_COOKIE['account_created']) && $_COOKIE['account_created'] == "true") {
    header('Location: ../public/compte_create.php');
    exit(); 
} else {
    if(isset($_POST['nom'], $_POST['prenom'], $_POST['e-mail'], $_POST['password'], $_POST['passwordConfirm'])) {
        add($_POST['nom'], $_POST['prenom'], $_POST['e-mail'], $_POST['password'], $_POST['passwordConfirm']);
    } else {
        header('Location: ../public/compte_create.php');
        exit();
    }
}
?> 