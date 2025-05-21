-- Datenbank erstellen, falls sie noch nicht existiert (optional)
CREATE DATABASE IF NOT EXISTS lernplattform_neu;
USE lernplattform_neu;

-- Tabelle für Benutzer
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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