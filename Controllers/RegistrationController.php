<?php

namespace Controllers;

use Models\UserModel;

class RegistrationController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function showRegistrationForm()
    {
        $content = '<h1>Registrierung</h1>';
        $content .= '<form action="/register" method="post">';
        $content .= '<table class="blueTable">';
        $content .= '<tbody>';
        $content .= '<tr><th><label for="username">Benutzername:</label></th><td><input type="text" id="username" name="username" required></td></tr>';
        $content .= '<tr><th><label for="email">E-Mail:</label></th><td><input type="email" id="email" name="email" required></td></tr>';
        $content .= '<tr><th><label for="password">Passwort:</label></th><td><input type="password" id="password" name="password" required></td></tr>';
        $content .= '<tr><td colspan="2"><button type="submit">Registrieren</button></td></tr>';
        $content .= '</tbody>';
        $content .= '</table>';
        $content .= '</form>';
        include VIEWS_PATH . 'layout.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Einfache Validierung (können wir später verbessern)
            if (empty($username) || empty($email) || empty($password)) {
                echo 'Bitte füllen Sie alle Felder aus.';
                return;
            }

            if ($this->userModel->getUserByUsername($username)) {
                echo 'Benutzername bereits vergeben.';
                return;
            }

            if ($this->userModel->getUserByEmail($email)) {
                echo 'E-Mail bereits registriert.';
                return;
            }

            if ($this->userModel->createUser($username, $password, $email)) {
                echo 'Registrierung erfolgreich. Sie können sich jetzt einloggen.';
            } else {
                echo 'Registrierung fehlgeschlagen. Bitte versuchen Sie es erneut.';
            }
        } else {
            // Wenn jemand versucht, direkt auf die /register-POST-Route zuzugreifen
            header("Location: /lernplattform/register");
            exit();
        }
    }
}