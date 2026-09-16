-- =====================================================================
--  Base de données : electrotech_store
--  Projet : ElectroTech Store — Plateforme web e-commerce
--  Stagiaire : ANDRIANARIVO Lalaina Bienvenu
--  SGBD : MySQL (moteur InnoDB, encodage utf8mb4)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS electrotech_store
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE electrotech_store;

-- ------------------------------------------------------------------
-- Table : users  (administrateurs et clients)
-- ------------------------------------------------------------------
CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  nom           VARCHAR(100)  NOT NULL,
  email         VARCHAR(150)  NOT NULL UNIQUE,
  mot_de_passe  VARCHAR(255)  NOT NULL,          -- stocke un HACHAGE (password_hash), jamais le mot de passe en clair
  role          ENUM('admin','client') NOT NULL DEFAULT 'client',
  telephone     VARCHAR(30),
  adresse       VARCHAR(255),
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------------
-- Table : categories
-- ------------------------------------------------------------------
CREATE TABLE categories (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nom         VARCHAR(100) NOT NULL,
  description TEXT
) ENGINE=InnoDB;

-- ------------------------------------------------------------------
-- Table : fournisseurs
-- ------------------------------------------------------------------
CREATE TABLE fournisseurs (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  nom       VARCHAR(150) NOT NULL,
  email     VARCHAR(150),
  telephone VARCHAR(30),
  adresse   VARCHAR(255)
) ENGINE=InnoDB;

-- ------------------------------------------------------------------
-- Table : produits
-- ------------------------------------------------------------------
CREATE TABLE produits (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  nom            VARCHAR(150)   NOT NULL,
  description    TEXT,
  categorie_id   INT            NOT NULL,
  fournisseur_id INT,
  prix_detail    DECIMAL(12,2)  NOT NULL DEFAULT 0,
  prix_gros      DECIMAL(12,2)  NOT NULL DEFAULT 0,
  quantite_stock INT            NOT NULL DEFAULT 0,
  seuil_alerte   INT            NOT NULL DEFAULT 5,
  image          VARCHAR(255),
  date_ajout     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (categorie_id)   REFERENCES categories(id),
  FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------------
-- Table : achats  (entrees de stock, en-tete)
-- ------------------------------------------------------------------
CREATE TABLE achats (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  fournisseur_id INT           NOT NULL,
  user_id        INT           NOT NULL,          -- l'administrateur qui enregistre l'achat
  date_achat     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  montant_total  DECIMAL(12,2) NOT NULL DEFAULT 0,
  FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id),
  FOREIGN KEY (user_id)        REFERENCES users(id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------------
-- Table : achat_details  (lignes d'un achat)
-- ------------------------------------------------------------------
CREATE TABLE achat_details (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  achat_id      INT           NOT NULL,
  produit_id    INT           NOT NULL,
  quantite      INT           NOT NULL,
  prix_unitaire DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (achat_id)   REFERENCES achats(id) ON DELETE CASCADE,
  FOREIGN KEY (produit_id) REFERENCES produits(id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------------
-- Table : commandes  (ventes, en-tete)
-- ------------------------------------------------------------------
CREATE TABLE commandes (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  client_id     INT           NOT NULL,
  date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  type_vente    ENUM('gros','detail') NOT NULL DEFAULT 'detail',
  statut        ENUM('en_attente','validee','livree','annulee') NOT NULL DEFAULT 'en_attente',
  montant_total DECIMAL(12,2) NOT NULL DEFAULT 0,
  FOREIGN KEY (client_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------------
-- Table : commande_details  (lignes d'une commande)
-- ------------------------------------------------------------------
CREATE TABLE commande_details (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  commande_id   INT           NOT NULL,
  produit_id    INT           NOT NULL,
  quantite      INT           NOT NULL,
  prix_unitaire DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
  FOREIGN KEY (produit_id)  REFERENCES produits(id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------------
-- Table : factures
-- ------------------------------------------------------------------
CREATE TABLE factures (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  commande_id    INT           NOT NULL,
  numero_facture VARCHAR(50)   NOT NULL UNIQUE,
  date_facture   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  montant_total  DECIMAL(12,2) NOT NULL DEFAULT 0,
  FOREIGN KEY (commande_id) REFERENCES commandes(id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------------
-- Donnees de depart : les 3 categories du sujet
-- ------------------------------------------------------------------
INSERT INTO categories (nom, description) VALUES
  ('Electronique',   'Televiseurs, audio, accessoires electroniques...'),
  ('Informatique',   'Ordinateurs, peripheriques, composants...'),
  ('Electromenager', 'Refrigerateurs, lave-linge, petit electromenager...');
