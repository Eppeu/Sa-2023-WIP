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
DROP TABLE IF EXISTS `soiree`;
DROP TABLE IF EXISTS `vote`;
DROP TABLE IF EXISTS `film`;
DROP TABLE IF EXISTS `utilisateur`;

-- A UTILISER A LA PREMIERE UTILISATION
-- CI DESSOUS LES CREATIONS DES TABLES
CREATE TABLE IF NOT EXISTS utilisateur (
    idUtilisateur INT AUTO_INCREMENT PRIMARY KEY  NOT NULL,
    nom_utilisateur VARCHAR(200) NOT NULL,
    prenom_utilisateur VARCHAR(200) NOT NULL,
    email VARCHAR(200)  NOT NULL,
    motDePasse VARCHAR(200)  NOT NULL
);

CREATE TABLE IF NOT EXISTS vote (
    idVote INT AUTO_INCREMENT PRIMARY KEY,
    choix INT,
    idUtilisateur int,
    FOREIGN KEY (idUtilisateur) REFERENCES utilisateur(idUtilisateur)
);

CREATE TABLE IF NOT EXISTS film (
    idFilm INT AUTO_INCREMENT PRIMARY KEY  NOT NULL,
    nom_film VARCHAR(200) NOT NULL,
    synopsis TEXT NOT NULL,
    genre VARCHAR(200) NOT NULL,
    dateSortie DATE NOT NULL,
    pathImage VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS soiree (
    idSoiree INT PRIMARY KEY NOT NULL,
    nom_soiree VARCHAR(200) NOT NULL,
    nbPersonneMax VARCHAR(200) NOT NULL,
    genre_soiree VARCHAR(200),
    dateDebut DATETIME NOT NULL,
    dateFin DATETIME NOT NULL,
    lieuChoisi VARCHAR(200),
    filmChoisi INT,
    choix1Film INT NOT NULL,
    choix2Film INT NOT NULL,
    choix3Film INT NOT NULL,
    choix4Film INT NOT NULL,
    choix5Film INT NOT NULL,
    choix1Lieu INT NOT NULL,
    choix2Lieu INT NOT NULL,
    idVoteFilm INT,
    idVoteLieu INT,
    FOREIGN KEY (idVoteFilm) REFERENCES vote(idVote),
    FOREIGN KEY (idVoteLieu) REFERENCES vote(idVote),
    FOREIGN KEY (filmChoisi) REFERENCES film(idFilm)
);