<?php
require_once './bdd/bdd_connexion.php';

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

            $creer = $bdd->prepare("INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, email, motDePasse)VALUES(?, ?, ?, ?)");
            $creer->execute(array($info1, $info2, $info3, $password));
            header('Location: ./connexion.html');
        }else{
            echo "Mot de passe incorrect";
            header('Location: ./new_account.php');
            
        }
    }else{
        echo "Veuillez compléter tous les champs.";
    }
}

add($_POST['nom'], $_POST['prenom'], $_POST['e-mail'], $_POST['password'], $_POST['passwordConfirm']);
?> 