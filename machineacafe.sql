-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 28 jan. 2018 à 22:11
-- Version du serveur :  5.7.19
-- Version de PHP :  7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `machineacafe`
--

-- --------------------------------------------------------

--
-- Structure de la table `boisson`
--

DROP TABLE IF EXISTS `boisson`;
CREATE TABLE IF NOT EXISTS `boisson` (
  `codeboisson` varchar(3) NOT NULL,
  `nomboisson` varchar(20) NOT NULL,
  `prixboisson` int(11) NOT NULL,
  PRIMARY KEY (`codeboisson`),
  UNIQUE KEY `codeboisson_UNIQUE` (`codeboisson`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `boisson`
--

INSERT INTO `boisson` (`codeboisson`, `nomboisson`, `prixboisson`) VALUES
('caf', 'Café Long', 60),
('cla', 'Café au Lait', 80),
('exp', 'Expresso', 50),
('lai', 'Lait', 80),
('the', 'Thé', 70);

-- --------------------------------------------------------

--
-- Structure de la table `boisson_ingredients`
--

DROP TABLE IF EXISTS `boisson_ingredients`;
CREATE TABLE IF NOT EXISTS `boisson_ingredients` (
  `boisson_codeboisson` varchar(3) NOT NULL,
  `ingredients_codeingredients` varchar(3) NOT NULL,
  `qteboisson` int(11) DEFAULT NULL,
  PRIMARY KEY (`boisson_codeboisson`,`ingredients_codeingredients`),
  KEY `fk_boisson_has_ingredients_ingredients1_idx` (`ingredients_codeingredients`),
  KEY `fk_boisson_has_ingredients_boisson1_idx` (`boisson_codeboisson`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `boisson_ingredients`
--

INSERT INTO `boisson_ingredients` (`boisson_codeboisson`, `ingredients_codeingredients`, `qteboisson`) VALUES
('caf', 'caf', 2),
('caf', 'eau', 2),
('cla', 'caf', 2),
('cla', 'eau', 2),
('cla', 'lai', 2),
('exp', 'caf', 1),
('exp', 'eau', 1),
('the', 'eau', 2),
('the', 'the', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
CREATE TABLE IF NOT EXISTS `ingredients` (
  `codeingredients` varchar(3) NOT NULL,
  `nomingredients` varchar(20) NOT NULL,
  `stockingredients` int(11) NOT NULL,
  PRIMARY KEY (`codeingredients`),
  UNIQUE KEY `codeingredients_UNIQUE` (`codeingredients`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ingredients`
--

INSERT INTO `ingredients` (`codeingredients`, `nomingredients`, `stockingredients`) VALUES
('caf', 'Café', 100),
('eau', 'Eau', 100),
('lai', 'Lait', 100),
('men', 'Menthe', 100),
('suc', 'Sucre', 300),
('the', 'Thé', 100);

-- --------------------------------------------------------

--
-- Structure de la table `vente`
--

DROP TABLE IF EXISTS `vente`;
CREATE TABLE IF NOT EXISTS `vente` (
  `codevente` int(11) NOT NULL AUTO_INCREMENT,
  `datevente` date NOT NULL,
  `heurevente` time NOT NULL,
  `nbsucres` int(1) NOT NULL DEFAULT '0',
  `boisson_codeboisson` varchar(3) NOT NULL,
  PRIMARY KEY (`codevente`),
  UNIQUE KEY `codevente_UNIQUE` (`codevente`),
  KEY `fk_vente_boisson1_idx` (`boisson_codeboisson`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `vente`
--

INSERT INTO `vente` (`codevente`, `datevente`, `heurevente`, `nbsucres`, `boisson_codeboisson`) VALUES
(1, '2018-01-04', '08:17:57', 0, 'caf'),
(2, '2018-01-04', '08:24:50', 1, 'caf'),
(3, '2018-01-04', '09:29:41', 0, 'exp'),
(4, '2018-01-04', '10:06:24', 1, 'exp'),
(5, '2018-01-04', '13:40:16', 5, 'the'),
(6, '2018-01-05', '08:34:49', 0, 'exp'),
(7, '2018-01-02', '18:34:49', 0, 'exp'),
(8, '2018-01-03', '10:13:28', 2, 'the'),
(9, '2018-01-05', '07:34:20', 5, 'caf'),
(10, '2018-01-05', '13:41:46', 5, 'the'),
(11, '2018-01-05', '16:53:59', 2, 'exp'),
(66, '2018-01-28', '18:31:24', 0, 'caf'),
(67, '2018-01-28', '18:33:27', 3, 'caf'),
(68, '2018-01-28', '18:33:34', 3, 'caf');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `boisson_ingredients`
--
ALTER TABLE `boisson_ingredients`
  ADD CONSTRAINT `fk_boisson_has_ingredients_boisson1` FOREIGN KEY (`boisson_codeboisson`) REFERENCES `boisson` (`codeboisson`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_boisson_has_ingredients_ingredients1` FOREIGN KEY (`ingredients_codeingredients`) REFERENCES `ingredients` (`codeingredients`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `vente`
--
ALTER TABLE `vente`
  ADD CONSTRAINT `fk_vente_boisson1` FOREIGN KEY (`boisson_codeboisson`) REFERENCES `boisson` (`codeboisson`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
