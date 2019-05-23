-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 13 Mai 2019 à 22:25
-- Version du serveur :  5.7.26-0ubuntu0.18.04.1
-- Version de PHP :  7.2.17-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `Trottinette`
--

-- --------------------------------------------------------

--
-- Structure de la table `erreur`
--

CREATE TABLE `erreur` (
  `id_erreur` int(11) NOT NULL,
  `id_station` int(11) NOT NULL,
  `id_trottinette` int(11) NOT NULL,
  `heure_signalement` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `erreur` text NOT NULL,
  `regler` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `erreur`
--

INSERT INTO `erreur` (`id_erreur`, `id_station`, `id_trottinette`, `heure_signalement`, `erreur`, `regler`) VALUES
(1, 1, 6, '2019-05-13 14:41:28', 'Moteur électrique Hs ', 1),
(2, 1, 4, '2019-05-13 15:41:58', 'erreur inconnu', 0),
(3, 1, 1, '2019-05-13 15:42:00', 'erreur inconnu', 0);

-- --------------------------------------------------------

--
-- Structure de la table `location`
--

CREATE TABLE `location` (
  `id_location` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_trottinette` int(11) NOT NULL,
  `date_emprunt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_rendu` datetime DEFAULT NULL,
  `cout` float DEFAULT NULL,
  `rendu` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `location`
--

INSERT INTO `location` (`id_location`, `id_utilisateur`, `id_trottinette`, `date_emprunt`, `date_rendu`, `cout`, `rendu`) VALUES
(34, 1, 1, '2019-05-13 21:05:54', '2019-05-13 21:05:56', 0.00333333, 1),
(35, 1, 1, '2019-05-13 21:06:00', '2019-05-13 21:06:04', 0.00666667, 1);

-- --------------------------------------------------------

--
-- Structure de la table `station`
--

CREATE TABLE `station` (
  `id_station` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `nb_trottinette` int(11) NOT NULL,
  `nb_max` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `station`
--

INSERT INTO `station` (`id_station`, `nom`, `adresse`, `nb_trottinette`, `nb_max`) VALUES
(1, 'Université de Perpignan', '52 av Paul alduy 66100 Perpignan', 10, 10),
(2, 'Castillet', 'Place de verdun 66000 Perpignan', 10, 10),
(3, 'Gare de perpignan', 'Galerie salvador dali 66000 Peprignan', 10, 10),
(4, 'Fnac', 'Place de Catalogne Espace 66000 Perpignan', 10, 10);

-- --------------------------------------------------------

--
-- Structure de la table `trottinette`
--

CREATE TABLE `trottinette` (
  `id_trottinette` int(11) NOT NULL,
  `id_station` int(11) NOT NULL,
  `a_quai` tinyint(1) NOT NULL,
  `en_panne` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `trottinette`
--

INSERT INTO `trottinette` (`id_trottinette`, `id_station`, `a_quai`, `en_panne`) VALUES
(1, 1, 1, 0),
(2, 1, 1, 0),
(3, 1, 1, 0),
(4, 1, 1, 0),
(5, 1, 1, 0),
(6, 1, 1, 0),
(7, 1, 1, 0),
(8, 1, 1, 0),
(9, 1, 1, 0),
(10, 1, 1, 0),
(11, 2, 1, 0),
(12, 2, 1, 0),
(13, 2, 1, 0),
(14, 2, 1, 0),
(15, 2, 1, 0),
(16, 2, 1, 0),
(17, 2, 1, 0),
(18, 2, 1, 0),
(19, 2, 1, 0),
(20, 2, 1, 0),
(21, 3, 1, 0),
(22, 3, 1, 0),
(23, 3, 1, 0),
(24, 3, 1, 0),
(25, 3, 1, 0),
(26, 3, 1, 0),
(27, 3, 1, 0),
(28, 3, 1, 0),
(29, 3, 1, 0),
(30, 3, 1, 0),
(31, 4, 1, 0),
(32, 4, 1, 0),
(33, 4, 1, 0),
(34, 4, 1, 0),
(35, 4, 1, 0),
(36, 4, 1, 0),
(37, 4, 1, 0),
(38, 4, 1, 0),
(39, 4, 1, 0),
(40, 4, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `identifiant` varchar(25) NOT NULL,
  `mot_de_passe` varchar(50) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `privilege` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `identifiant`, `mot_de_passe`, `nom`, `prenom`, `adresse`, `privilege`) VALUES
(1, 'projetBDD', '127c0093633ff327b7ca7e327d81cd949d1d0b21', 'aucun', 'aucun', 'aucune', 2),
(2, 'Test66', 'e1051e880ef5c449104bc952e2b260dc9da3ee21', 'Test66', 'Test66', 'Test66', 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `erreur`
--
ALTER TABLE `erreur`
  ADD PRIMARY KEY (`id_erreur`),
  ADD KEY `id_trottinette` (`id_trottinette`),
  ADD KEY `id_station` (`id_station`);

--
-- Index pour la table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id_location`),
  ADD KEY `id_trottinette` (`id_trottinette`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `station`
--
ALTER TABLE `station`
  ADD PRIMARY KEY (`id_station`);

--
-- Index pour la table `trottinette`
--
ALTER TABLE `trottinette`
  ADD PRIMARY KEY (`id_trottinette`),
  ADD KEY `id_station` (`id_station`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `erreur`
--
ALTER TABLE `erreur`
  MODIFY `id_erreur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `location`
--
ALTER TABLE `location`
  MODIFY `id_location` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT pour la table `station`
--
ALTER TABLE `station`
  MODIFY `id_station` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `trottinette`
--
ALTER TABLE `trottinette`
  MODIFY `id_trottinette` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `erreur`
--
ALTER TABLE `erreur`
  ADD CONSTRAINT `erreur_station` FOREIGN KEY (`id_station`) REFERENCES `station` (`id_station`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `erreur_trottinete` FOREIGN KEY (`id_trottinette`) REFERENCES `trottinette` (`id_trottinette`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_trottinette` FOREIGN KEY (`id_trottinette`) REFERENCES `trottinette` (`id_trottinette`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `location_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `trottinette`
--
ALTER TABLE `trottinette`
  ADD CONSTRAINT `trottinette_station` FOREIGN KEY (`id_station`) REFERENCES `station` (`id_station`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
