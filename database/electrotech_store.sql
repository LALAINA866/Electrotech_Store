-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 23 sep. 2026 à 21:20
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `electrotech_store`
--

-- --------------------------------------------------------

--
-- Structure de la table `achats`
--

CREATE TABLE `achats` (
  `id` int(11) NOT NULL,
  `fournisseur_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_achat` timestamp NOT NULL DEFAULT current_timestamp(),
  `montant_total` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `achats`
--

INSERT INTO `achats` (`id`, `fournisseur_id`, `user_id`, `date_achat`, `montant_total`) VALUES
(4, 4, 5, '2026-09-21 15:26:44', 58800.00),
(5, 2, 5, '2026-09-21 20:19:44', 40200.00);

-- --------------------------------------------------------

--
-- Structure de la table `achat_details`
--

CREATE TABLE `achat_details` (
  `id` int(11) NOT NULL,
  `achat_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `achat_details`
--

INSERT INTO `achat_details` (`id`, `achat_id`, `produit_id`, `quantite`, `prix_unitaire`) VALUES
(10, 4, 4, 10, 5880.00),
(11, 5, 3, 2, 2250.00),
(12, 5, 4, 7, 5100.00);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `description`) VALUES
(1, 'Electronique', 'Televiseurs, audio, accessoires electroniques...'),
(2, 'Informatique', 'Ordinateurs, peripheriques, composants...'),
(3, 'Electromenager', 'Refrigerateurs, lave-linge, petit electromenager...');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `type_vente` enum('gros','detail') NOT NULL DEFAULT 'detail',
  `statut` enum('en_attente','validee','livree','annulee') NOT NULL DEFAULT 'en_attente',
  `montant_total` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `client_id`, `date_commande`, `type_vente`, `statut`, `montant_total`) VALUES
(1, 4, '2026-09-22 19:38:32', 'detail', 'en_attente', 38950.00),
(2, 4, '2026-09-22 19:45:43', 'detail', 'en_attente', 44020.00),
(4, 6, '2026-09-22 19:52:45', 'detail', 'en_attente', 23250.00);

-- --------------------------------------------------------

--
-- Structure de la table `commande_details`
--

CREATE TABLE `commande_details` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande_details`
--

INSERT INTO `commande_details` (`id`, `commande_id`, `produit_id`, `quantite`, `prix_unitaire`) VALUES
(1, 1, 3, 9, 2550.00),
(2, 1, 4, 5, 3200.00),
(3, 2, 3, 11, 2570.00),
(4, 2, 4, 5, 3150.00),
(5, 4, 3, 5, 2700.00),
(6, 4, 4, 3, 3250.00);

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `numero_facture` varchar(50) NOT NULL,
  `date_facture` timestamp NOT NULL DEFAULT current_timestamp(),
  `montant_total` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`id`, `commande_id`, `numero_facture`, `date_facture`, `montant_total`) VALUES
(1, 4, 'FACT-20260923-4', '2026-09-23 18:57:59', 23250.00);

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `nom`, `email`, `telephone`, `adresse`) VALUES
(2, 'xxxxxx', 'xxxxx@gmail.com', '+261332211100', 'XXX'),
(3, 'yyyyyy', 'yyyyy@gmail.com', '+261370000010', 'YYY'),
(4, 'zzzzzz', 'zzzzz@gmail.com', '+261341155522', 'ZZZ');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `categorie_id` int(11) NOT NULL,
  `fournisseur_id` int(11) DEFAULT NULL,
  `prix_detail` decimal(12,2) NOT NULL DEFAULT 0.00,
  `prix_gros` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantite_stock` int(11) NOT NULL DEFAULT 0,
  `seuil_alerte` int(11) NOT NULL DEFAULT 5,
  `image` varchar(255) DEFAULT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `categorie_id`, `fournisseur_id`, `prix_detail`, `prix_gros`, `quantite_stock`, `seuil_alerte`, `image`, `date_ajout`) VALUES
(1, 'Souris', 'noir', 2, 2, 1500.00, 1000.00, 25, 5, '1789670260_img_souris.jpg', '2026-09-17 18:33:21'),
(3, 'Ordinateur', 'laptop', 2, 3, 2500.00, 2002.00, 15, 5, '1789737469_img_PC.jpg', '2026-09-18 13:17:49'),
(4, 'Réfrigérateur', '2 portes, gris', 3, 4, 3000.00, 2400.00, 7, 5, '1789754385_img_refrigerateur.jpg', '2026-09-18 17:59:45');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','client') NOT NULL DEFAULT 'client',
  `telephone` varchar(30) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `telephone`, `adresse`, `date_creation`) VALUES
(4, 'Andria', 'andria@gmail.com', '$2y$10$RO079PsJhlZo0wuRYUKgqOtp.TaX2zrs4vbNAQoxmx96IO2FSm8VS', 'client', NULL, NULL, '2026-09-16 11:35:14'),
(5, 'Administrateur', 'admin@electrotech.mg', '$2y$10$IkmcNshg62wFUGEIF4/RLu6rKKQr6TPcWwFon/4ff4aUtYbpX20Iu', 'admin', NULL, NULL, '2026-09-16 15:26:02'),
(6, 'userx', 'userx@gmail.com', '$2y$10$aaHESKjtA4dVNJkCwfeqvevXlAyz2PsEQNr9OWJ7Gwe6Z/8/1Y8PW', 'client', NULL, NULL, '2026-09-22 19:32:40');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achats`
--
ALTER TABLE `achats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fournisseur_id` (`fournisseur_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `achat_details`
--
ALTER TABLE `achat_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `achat_id` (`achat_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Index pour la table `commande_details`
--
ALTER TABLE `commande_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_facture` (`numero_facture`),
  ADD KEY `commande_id` (`commande_id`);

--
-- Index pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `fournisseur_id` (`fournisseur_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `achats`
--
ALTER TABLE `achats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `achat_details`
--
ALTER TABLE `achat_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `commande_details`
--
ALTER TABLE `commande_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achats`
--
ALTER TABLE `achats`
  ADD CONSTRAINT `achats_ibfk_1` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`),
  ADD CONSTRAINT `achats_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `achat_details`
--
ALTER TABLE `achat_details`
  ADD CONSTRAINT `achat_details_ibfk_1` FOREIGN KEY (`achat_id`) REFERENCES `achats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `achat_details_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `commande_details`
--
ALTER TABLE `commande_details`
  ADD CONSTRAINT `commande_details_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commande_details_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`);

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
