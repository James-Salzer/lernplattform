<?php

namespace Models;

use PDO;
use PDOException;

require_once ROOT_PATH . 'Database.php';

class UserModel
{
    
    private $db;

    public function __construct()
    {
        $this->db = \Database::getInstance();
    }

    public function createUser($username, $password, $email)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, email, rolle_id, status_id)
                VALUES (:username, :password, :email,
                        (SELECT id FROM rollen WHERE name = 'user'),
                        (SELECT id FROM status WHERE name = 'aktiv'))";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        } catch (PDOException $e) {
            log_message("Fehler beim Erstellen des Benutzers: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByUsername($username)
    {
        $sql = "SELECT u.*, r.name AS rolle_name, s.name AS status_name
                FROM users u
                INNER JOIN rollen r ON u.rolle_id = r.id
                INNER JOIN status s ON u.status_id = s.id
                WHERE u.username = :username";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Fehler beim Abrufen des Benutzers nach Benutzername: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT u.*, r.name AS rolle_name, s.name AS status_name
                FROM users u
                INNER JOIN rollen r ON u.rolle_id = r.id
                INNER JOIN status s ON u.status_id = s.id
                WHERE u.email = :email";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Fehler beim Abrufen des Benutzers nach E-Mail: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsers()
    {
        $sql = "SELECT u.id, u.username, u.email, r.name AS rolle_name, s.name AS status_name
                FROM users u
                INNER JOIN rollen r ON u.rolle_id = r.id
                INNER JOIN status s ON u.status_id = s.id
                ORDER BY u.username ASC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Fehler beim Abrufen aller Benutzer: " . $e->getMessage());
            return false;
        }
    }
}