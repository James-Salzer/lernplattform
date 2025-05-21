-- Datenbank löschen und neu erstellen (VORSICHT: Datenverlust!)
DROP DATABASE IF EXISTS lernplattform_neu;
CREATE DATABASE lernplattform_neu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lernplattform_neu;

-- Tabelle für Rollen
CREATE TABLE rollen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Tabelle für Status
CREATE TABLE status (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Tabelle für Benutzer
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    rolle_id INT NOT NULL,
    status_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rolle_id) REFERENCES rollen(id),
    FOREIGN KEY (status_id) REFERENCES status(id)
);

-- Grundlegende Rollen einfügen
INSERT INTO rollen (name) VALUES ('admin'), ('trainer'), ('user');

-- Grundlegende Status einfügen
INSERT INTO status (name) VALUES ('aktiv'), ('inaktiv'), ('gesperrt');

-- Beispiel-Admin-Benutzer einfügen
INSERT INTO users (username, password, email, rolle_id, status_id)
VALUES ('admin', '$2y$10$aXWvXZAZgLpr2jMbIft8ou3.B.LqdUkRKxhWcs9gwHsV25MZ3zTDm', 'admin@example.com',
        (SELECT id FROM rollen WHERE name = 'admin'),
        (SELECT id FROM status WHERE name = 'aktiv'));

-- Beispiel-Trainer-Benutzer einfügen
INSERT INTO users (username, password, email, rolle_id, status_id)
VALUES ('trainer', '$2y$10$aXWvXZAZgLpr2jMbIft8ou3.B.LqdUkRKxhWcs9gwHsV25MZ3zTDm', 'trainer@example.com',
        (SELECT id FROM rollen WHERE name = 'trainer'),
        (SELECT id FROM status WHERE name = 'aktiv'));

-- Beispiel-Normaler Benutzer einfügen
INSERT INTO users (username, password, email, rolle_id, status_id)
VALUES ('user', '$2y$10$aXWvXZAZgLpr2jMbIft8ou3.B.LqdUkRKxhWcs9gwHsV25MZ3zTDm', 'user@example.com',
        (SELECT id FROM rollen WHERE name = 'user'),
        (SELECT id FROM status WHERE name = 'aktiv'));

-- Tabelle für Schulungen
CREATE TABLE IF NOT EXISTS trainings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

-- Tabelle für Materialien
CREATE TABLE IF NOT EXISTS materials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Verbindungstabelle für Trainings und Materialien (many-to-many)
CREATE TABLE IF NOT EXISTS training_materials (
    training_id INT UNSIGNED NOT NULL,
    material_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (training_id, material_id),
    FOREIGN KEY (training_id) REFERENCES trainings(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
);

-- Tabelle für Fragen
CREATE TABLE IF NOT EXISTS questions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL
);

-- Verbindungstabelle für Trainings und Fragen (many-to-many)
CREATE TABLE IF NOT EXISTS training_questions (
    training_id INT UNSIGNED NOT NULL,
    question_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (training_id, question_id),
    FOREIGN KEY (training_id) REFERENCES trainings(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- Tabelle für Antworten
CREATE TABLE IF NOT EXISTS answers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_id INT UNSIGNED NOT NULL,
    answer_text TEXT NOT NULL,
    is_correct BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- Tabelle für Zertifikatsvorlagen
CREATE TABLE IF NOT EXISTS certificate_templates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Verbindungstabelle für Trainings und Zertifikatsvorlagen (mit Feldzuordnung)
CREATE TABLE IF NOT EXISTS training_certificate_templates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    training_id INT UNSIGNED NOT NULL,
    template_id INT UNSIGNED NOT NULL,
    field_mapping JSON NOT NULL COMMENT 'Enthält die Positionen und Größen der Textfelder für diese Schulung und Vorlage',
    FOREIGN KEY (training_id) REFERENCES trainings(id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES certificate_templates(id) ON DELETE CASCADE,
    UNIQUE KEY (training_id, template_id)
);