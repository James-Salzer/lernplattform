<?php

namespace Controllers;

use Models\UserModel;

class LoginController
{
    private $userModel;
    private $sessionManager;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->sessionManager = new \SessionManager();
    }

    public function showLoginForm()
    {
        $content = '<h1>Einloggen</h1>';
        $content .= '<form action="/lernplattform/login" method="post">';
        $content .= '<table class="blueTable">';
        $content .= '<tbody>';
        $content .= '<tr><th><label for="username">Benutzername:</label></th><td><input type="text" id="username" name="username" required></td></tr>';
        $content .= '<tr><th><label for="password">Passwort:</label></th><td><input type="password" id="password" name="password" required></td></tr>';
        $content .= '<tr><td colspan="2"><button type="submit">Einloggen</button></td></tr>';
        $content .= '</tbody>';
        $content .= '</table>';
        $content .= '</form>';
        include VIEWS_PATH . 'layout.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $content = '<p class="error">Bitte geben Sie Benutzername und Passwort ein.</p>';
            } else {
                $user = $this->userModel->getUserByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    // Erfolgreiche Anmeldung
                    $this->sessionManager->setUserSession($user['id'], $user['username'], $user['rolle_name']);
                    header("Location: /lernplattform/dashboard");
                    exit();
                } else {
                    $content = '<p class="error">Ungültiger Benutzername oder Passwort.</p>';
                }
            }
            // Fehlerfall: Formular erneut anzeigen
            $content .= '<h1>Einloggen</h1>';
            $content .= '<form action="/lernplattform/login" method="post">';
            $content .= '<table class="blueTable">';
            $content .= '<tbody>';
            $content .= '<tr><th><label for="username">Benutzername:</label></th><td><input type="text" id="username" name="username" required></td></tr>';
            $content .= '<tr><th><label for="password">Passwort:</label></th><td><input type="password" id="password" name="password" required></td></tr>';
            $content .= '<tr><td colspan="2"><button type="submit">Einloggen</button></td></tr>';
            $content .= '</tbody>';
            $content .= '</table>';
            $content .= '</form>';
            include VIEWS_PATH . 'layout.php';
        } else {
            $this->showLoginForm();
        }
    }

    public function logout()
    {
        $this->sessionManager->destroy();
        header("Location: /lernplattform/login");
        exit();
    }
}