-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 10 déc. 2024 à 16:15
-- Version du serveur : 10.6.18-MariaDB-0ubuntu0.22.04.1
-- Version de PHP : 8.1.2-1ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `formulaire_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `formulaire`
--

CREATE TABLE `formulaire` (
  `id` int(11) NOT NULL,
  `date_creation` datetime NOT NULL,
  `num_partners` int(11) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `partnership_name` varchar(255) NOT NULL,
  `official_address` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `profit_loss_distribution` varchar(255) NOT NULL,
  `signing_partner_count` int(11) NOT NULL,
  `country_code` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `formulaire`
--

INSERT INTO `formulaire` (`id`, `date_creation`, `num_partners`, `activity_type`, `partnership_name`, `official_address`, `start_date`, `end_date`, `profit_loss_distribution`, `signing_partner_count`, `country_code`) VALUES
(1, '2024-12-10 14:02:41', 3, 'Commerce', 'Partenariat Alpha', '123 Rue de Paris, Paris', '2023-01-01', '2023-12-31', '50-50', 2, 'DE'),
(2, '2023-02-01 11:00:00', 2, 'Technologie', 'Partenariat Beta', '456 Avenue de Lyon, Lyon', '2023-02-01', '2023-11-30', '60-40', 1, 'FR'),
(3, '2023-03-01 12:00:00', 4, 'Santé', 'Partenariat Gamma', '789 Boulevard de Marseille, Marseille', '2023-03-01', '2023-10-31', '70-30', 3, 'FR'),
(4, '2023-04-01 13:00:00', 3, 'Éducation', 'Partenariat Delta', '101 Rue de Bordeaux, Bordeaux', '2023-04-01', '2023-09-30', '80-20', 2, 'FR'),
(5, '2023-05-01 14:00:00', 2, 'Finance', 'Partenariat Epsilon', '202 Avenue de Nice, Nice', '2023-05-01', '2023-08-31', '50-50', 1, 'FR'),
(6, '2023-06-01 15:00:00', 5, 'Agriculture', 'Partenariat Zeta', '303 Boulevard de Toulouse, Toulouse', '2023-06-01', '2023-12-31', '60-40', 4, 'FR'),
(7, '2023-07-01 16:00:00', 3, 'Transport', 'Partenariat Eta', '404 Rue de Nantes, Nantes', '2023-07-01', '2023-12-31', '70-30', 2, 'FR'),
(8, '2023-08-01 17:00:00', 4, 'Tourisme', 'Partenariat Theta', '505 Avenue de Strasbourg, Strasbourg', '2023-08-01', '2023-12-31', '80-20', 3, 'FR'),
(9, '2023-09-01 18:00:00', 2, 'Immobilier', 'Partenariat Iota', '606 Boulevard de Lille, Lille', '2023-09-01', '2023-12-31', '50-50', 1, 'FR'),
(10, '2023-10-01 19:00:00', 3, 'Énergie', 'Partenariat Kappa', '707 Rue de Rennes, Rennes', '2023-10-01', '2023-12-31', '60-40', 2, 'FR');

-- --------------------------------------------------------

--
-- Structure de la table `partenaire`
--

CREATE TABLE `partenaire` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `partenaire`
--

INSERT INTO `partenaire` (`id`, `nom`) VALUES
(1, 'Judy'),
(2, 'Bob'),
(3, 'Charlie'),
(4, 'David'),
(5, 'Eve'),
(6, 'Frank'),
(7, 'Grace'),
(8, 'Heidi'),
(9, 'Ivan'),
(10, 'Judy');

-- --------------------------------------------------------

--
-- Structure de la table `partenaire_formulaire`
--

CREATE TABLE `partenaire_formulaire` (
  `formulaire_id` int(11) NOT NULL,
  `partenaire_id` int(11) NOT NULL,
  `contribution` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `partenaire_formulaire`
--

INSERT INTO `partenaire_formulaire` (`formulaire_id`, `partenaire_id`, `contribution`) VALUES
(1, 1, 'Investissement initial'),
(1, 2, 'Support technique'),
(1, 3, 'Marketing'),
(2, 4, 'Développement logiciel'),
(2, 5, 'Gestion de projet'),
(3, 6, 'Recherche médicale'),
(3, 7, 'Financement'),
(3, 8, 'Logistique'),
(3, 9, 'Communication'),
(4, 1, 'Matériel pédagogique'),
(4, 2, 'Support en ligne'),
(4, 10, 'Formation'),
(5, 3, 'Analyse financière'),
(5, 4, 'Conseil en investissement'),
(6, 5, 'Agriculture biologique'),
(6, 6, 'Distribution'),
(6, 7, 'Marketing'),
(6, 8, 'Recherche et développement'),
(6, 9, 'Logistique'),
(7, 1, 'Gestion des opérations'),
(7, 2, 'Support technique'),
(7, 10, 'Transport de marchandises'),
(8, 3, 'Développement touristique'),
(8, 4, 'Marketing'),
(8, 5, 'Gestion des opérations'),
(8, 6, 'Support technique'),
(9, 7, 'Investissement immobilier'),
(9, 8, 'Gestion des biens'),
(10, 9, 'Production d\'énergie'),
(10, 10, 'Distribution');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `formulaire`
--
ALTER TABLE `formulaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `partenaire`
--
ALTER TABLE `partenaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `partenaire_formulaire`
--
ALTER TABLE `partenaire_formulaire`
  ADD PRIMARY KEY (`formulaire_id`,`partenaire_id`),
  ADD KEY `partenaire_id` (`partenaire_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `formulaire`
--
ALTER TABLE `formulaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `partenaire`
--
ALTER TABLE `partenaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `partenaire_formulaire`
--
ALTER TABLE `partenaire_formulaire`
  ADD CONSTRAINT `partenaire_formulaire_ibfk_1` FOREIGN KEY (`formulaire_id`) REFERENCES `formulaire` (`id`),
  ADD CONSTRAINT `partenaire_formulaire_ibfk_2` FOREIGN KEY (`partenaire_id`) REFERENCES `partenaire` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
