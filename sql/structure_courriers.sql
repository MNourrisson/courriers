-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 07 Février 2018 à 09:49
-- Version du serveur :  5.5.54-0+deb7u1
-- Version de PHP :  5.6.29-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `courriers`
--

-- --------------------------------------------------------

--
-- Structure de la table `arrive`
--

CREATE TABLE IF NOT EXISTS `arrive` (
`id_arrive` int(11) NOT NULL,
  `date` date NOT NULL,
  `expediteur` varchar(255) NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `techniciens` varchar(255) NOT NULL,
  `id_courrier_reponse` varchar(50) NOT NULL,
  `id_personne` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `depart`
--

CREATE TABLE IF NOT EXISTS `depart` (
`id_depart` int(11) NOT NULL,
  `id_formate` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `destinataire` text NOT NULL,
  `redacteur` varchar(25) NOT NULL,
  `nb` int(11) NOT NULL,
  `objet` varchar(255) NOT NULL,
  `id_personne` int(11) NOT NULL,
  `competence` enum('Charte','Parc','Pays','SAGE','SCoT') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `lien`
--

CREATE TABLE IF NOT EXISTS `lien` (
`id_lien` int(11) NOT NULL,
  `id_depart` int(11) NOT NULL,
  `lien` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE IF NOT EXISTS `personne` (
`id_personne` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `droit` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `arrive`
--
ALTER TABLE `arrive`
 ADD PRIMARY KEY (`id_arrive`), ADD KEY `id_personne` (`id_personne`);

--
-- Index pour la table `depart`
--
ALTER TABLE `depart`
 ADD PRIMARY KEY (`id_depart`), ADD UNIQUE KEY `id_formate` (`id_formate`), ADD KEY `id_personne` (`id_personne`);

--
-- Index pour la table `lien`
--
ALTER TABLE `lien`
 ADD PRIMARY KEY (`id_lien`), ADD KEY `fk_id_depart` (`id_depart`);

--
-- Index pour la table `personne`
--
ALTER TABLE `personne`
 ADD PRIMARY KEY (`id_personne`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `arrive`
--
ALTER TABLE `arrive`
MODIFY `id_arrive` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `depart`
--
ALTER TABLE `depart`
MODIFY `id_depart` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `lien`
--
ALTER TABLE `lien`
MODIFY `id_lien` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
MODIFY `id_personne` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `arrive`
--
ALTER TABLE `arrive`
ADD CONSTRAINT `arrive_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`);

--
-- Contraintes pour la table `depart`
--
ALTER TABLE `depart`
ADD CONSTRAINT `depart_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`);

--
-- Contraintes pour la table `lien`
--
ALTER TABLE `lien`
ADD CONSTRAINT `fk_id_depart` FOREIGN KEY (`id_depart`) REFERENCES `depart` (`id_depart`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
