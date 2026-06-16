--
--
-- CI-DESSOUS LES REQUETES SQL QUE VOUS POUVEZ AVOIR BESOIN D'UTILISER POUR UTILISER LA BDD
--
--

-- Suppression de la base de données, A N'UTILISER QUE SI EXTREME BESOIN
drop schema if exists `popco_bdd`;

-- A UTILISER A LA PREMIERE UTILISATION
-- Création de la base de données
CREATE SCHEMA IF NOT EXISTS `popco_bdd`;

-- A UTILISER A LA PREMIERE UTILISATION
-- Utilise la base de données en question, A N'UTILISER QUE SI BESOIN
USE `popco_bdd`;

-- Efface les tables contenant les données si besoin, A N'UTILISER QUE SI BESOIN
DROP TABLE IF EXISTS `vote`;
DROP TABLE IF EXISTS `lieu`;
DROP TABLE IF EXISTS `soiree`;
DROP TABLE IF EXISTS `film`;
DROP TABLE IF EXISTS `utilisateur`;

-- A UTILISER A LA PREMIERE UTILISATION
-- CI DESSOUS LES CREATIONS DES TABLES
CREATE TABLE IF NOT EXISTS utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY  NOT NULL,
    nom_utilisateur VARCHAR(100) NOT NULL,
    prenom_utilisateur VARCHAR(100) NOT NULL,
    email VARCHAR(50)  NOT NULL,
    mot_de_passe VARCHAR(200)  NOT NULL,
    is_admin BOOLEAN NOT NULL
);

CREATE TABLE IF NOT EXISTS film (
    id_film INT AUTO_INCREMENT PRIMARY KEY  NOT NULL,
    nom_film VARCHAR(100) NOT NULL,
    synopsis TEXT NOT NULL,
    genre VARCHAR(50) NOT NULL,
    date_sortie YEAR NOT NULL,
    affiche VARCHAR(300) NOT NULL
);

CREATE TABLE IF NOT EXISTS lieu (
    id_lieu INT AUTO_INCREMENT PRIMARY KEY  NOT NULL,
    adresse VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS soiree (
    id_soiree INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_utilisateur INT NOT NULL,
    nom_soiree VARCHAR(200) NOT NULL,
    description_soiree TEXT NOT NULL,
    nb_personne_max INT NOT NULL,
    genre_soiree VARCHAR(50),
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    date_limite_vote DATETIME NOT NULL, -- AJOUT RECENT
    lieu_choisi INT,
    film_choisi INT,
    choix_1_film INT NOT NULL,
    choix_2_film INT NOT NULL,
    choix_3_film INT NOT NULL,
    choix_4_film INT NOT NULL,
    choix_5_film INT NOT NULL,
    choix_1_lieu INT NOT NULL,
    choix_2_lieu INT NOT NULL,
    choix_3_lieu INT NOT NULL,
    image_soiree VARCHAR(200)
);

CREATE TABLE IF NOT EXISTS vote (
    id_soiree INT NOT NULL,
    id_utilisateur INT NOT NULL,
    choix_film INT NOT NULL,
    choix_lieu INT NOT NULL,
    PRIMARY KEY (id_soiree, id_utilisateur),
    FOREIGN KEY (id_soiree) REFERENCES soiree(id_soiree),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (choix_film) REFERENCES film(id_film),
    FOREIGN KEY (choix_lieu) REFERENCES lieu(id_lieu)
);