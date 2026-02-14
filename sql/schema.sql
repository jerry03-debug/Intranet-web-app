-- Base de données SmartTech Intranet
CREATE DATABASE IF NOT EXISTS smarttech_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smarttech_db;

-- Table des employés (CRUD principal)
CREATE TABLE IF NOT EXISTS employes (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(100) NOT NULL,
    prenom      VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    poste       VARCHAR(100) NOT NULL,
    departement ENUM('Informatique','Ressources Humaines','Finance','Marketing','Direction') NOT NULL,
    date_embauche DATE NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table de logs pour les notifications
CREATE TABLE IF NOT EXISTS action_logs (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    action     ENUM('CREATE','UPDATE','DELETE') NOT NULL,
    table_name VARCHAR(50) NOT NULL,
    record_id  INT NOT NULL,
    details    TEXT,
    user_ip    VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Données de démonstration
INSERT INTO employes (nom, prenom, email, poste, departement, date_embauche) VALUES
('Diop', 'Moussa', 'moussa.diop@smarttech.sn', 'Développeur', 'Informatique', '2024-01-15'),
('Ndiaye', 'Fatou', 'fatou.ndiaye@smarttech.sn', 'Comptable', 'Finance', '2023-06-01'),
('Fall', 'Ibrahima', 'ibrahima.fall@smarttech.sn', 'Directeur RH', 'Ressources Humaines', '2022-03-10');

-- Utilisateur MySQL pour l'application
CREATE USER IF NOT EXISTS 'smarttech_user'@'localhost' IDENTIFIED BY 'SmartT3ch_2025!';
GRANT ALL PRIVILEGES ON smarttech_db.* TO 'smarttech_user'@'localhost';
FLUSH PRIVILEGES;
