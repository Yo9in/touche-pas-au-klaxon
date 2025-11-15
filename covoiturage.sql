-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : sam. 15 nov. 2025 à 11:29
-- Version du serveur : 5.7.39
-- Version de PHP : 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `covoiturage`
--

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

CREATE TABLE `agence` (
  `id_agence` int(11) NOT NULL,
  `nom` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`id_agence`, `nom`) VALUES
(1, 'Paris'),
(2, 'Lyon'),
(3, 'Marseille'),
(4, 'Toulouse'),
(5, 'Nice'),
(6, 'Nantes'),
(7, 'Strasbourg'),
(8, 'Montpellier'),
(9, 'Bordeaux'),
(10, 'Lille'),
(11, 'Rennes'),
(12, 'Reims');

-- --------------------------------------------------------

--
-- Structure de la table `trajet`
--

CREATE TABLE `trajet` (
  `id_trajet` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `agence_depart_id` int(11) NOT NULL,
  `agence_arrivee_id` int(11) NOT NULL,
  `date_heure_depart` datetime NOT NULL,
  `date_heure_arrivee` datetime NOT NULL,
  `nb_places_total` int(11) NOT NULL,
  `nb_places_disponibles` int(11) NOT NULL,
  `commentaire` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trajet`
--

INSERT INTO `trajet` (`id_trajet`, `utilisateur_id`, `agence_depart_id`, `agence_arrivee_id`, `date_heure_depart`, `date_heure_arrivee`, `nb_places_total`, `nb_places_disponibles`, `commentaire`, `created_at`) VALUES
(1, 1, 1, 2, '2025-11-15 08:30:00', '2025-11-15 12:00:00', 4, 2, 'Aller réunion Lyon', '2025-11-11 18:35:06'),
(2, 2, 2, 1, '2025-11-16 17:00:00', '2025-11-16 21:00:00', 3, 1, 'Retour Paris', '2025-11-11 18:35:06'),
(3, 3, 1, 5, '2025-11-18 07:45:00', '2025-11-18 11:30:00', 4, 4, 'Déplacement Nice', '2025-11-11 18:35:06'),
(4, 1, 1, 6, '2025-11-27 10:30:00', '2025-11-27 13:59:00', 4, 4, NULL, '2025-11-14 19:43:03');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `nom`, `prenom`, `telephone`, `email`, `mot_de_passe`, `role`, `created_at`) VALUES
(1, 'Martin', 'Alexandre', '0612345678', 'alexandre.martin@email.fr', 'changeme', 'admin', '2025-11-11 18:35:06'),
(2, 'Dubois', 'Sophie', '0698765432', 'sophie.dubois@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(3, 'Bernard', 'Julien', '0622446688', 'julien.bernard@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(4, 'Moreau', 'Camille', '0611223344', 'camille.moreau@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(5, 'Lefèvre', 'Lucie', '0777889900', 'lucie.lefevre@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(6, 'Leroy', 'Thomas', '0655443322', 'thomas.leroy@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(7, 'Roux', 'Chloé', '0633221199', 'chloe.roux@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(8, 'Petit', 'Maxime', '0766778899', 'maxime.petit@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(9, 'Garnier', 'Laura', '0688776655', 'laura.garnier@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(10, 'Dupuis', 'Antoine', '0744556677', 'antoine.dupuis@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(11, 'Lefebvre', 'Emma', '0699887766', 'emma.lefebvre@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(12, 'Fontaine', 'Louis', '0655667788', 'louis.fontaine@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(13, 'Chevalier', 'Clara', '0788990011', 'clara.chevalier@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(14, 'Robin', 'Nicolas', '0644332211', 'nicolas.robin@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(15, 'Gauthier', 'Marine', '0677889922', 'marine.gauthier@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(16, 'Fournier', 'Pierre', '0722334455', 'pierre.fournier@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(17, 'Girard', 'Sarah', '0688665544', 'sarah.girard@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(18, 'Lambert', 'Hugo', '0611223366', 'hugo.lambert@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(19, 'Masson', 'Julie', '0733445566', 'julie.masson@email.fr', 'changeme', 'user', '2025-11-11 18:35:06'),
(20, 'Henry', 'Arthur', '0666554433', 'arthur.henry@email.fr', 'changeme', 'user', '2025-11-11 18:35:06');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agence`
--
ALTER TABLE `agence`
  ADD PRIMARY KEY (`id_agence`);

--
-- Index pour la table `trajet`
--
ALTER TABLE `trajet`
  ADD PRIMARY KEY (`id_trajet`),
  ADD KEY `fk_trajet_utilisateur` (`utilisateur_id`),
  ADD KEY `fk_trajet_agence_depart` (`agence_depart_id`),
  ADD KEY `fk_trajet_agence_arrivee` (`agence_arrivee_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agence`
--
ALTER TABLE `agence`
  MODIFY `id_agence` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `trajet`
--
ALTER TABLE `trajet`
  MODIFY `id_trajet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `trajet`
--
ALTER TABLE `trajet`
  ADD CONSTRAINT `fk_trajet_agence_arrivee` FOREIGN KEY (`agence_arrivee_id`) REFERENCES `agence` (`id_agence`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trajet_agence_depart` FOREIGN KEY (`agence_depart_id`) REFERENCES `agence` (`id_agence`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trajet_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


