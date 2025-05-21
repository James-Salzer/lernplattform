<?php

namespace Controllers;

class AdminController
{
    private $sessionManager;

    public function __construct()
    {
        $this->sessionManager = new \SessionManager();
    }

    public function showAdminDashboard()
    {
        if ($this->sessionManager->isLoggedIn() && $this->sessionManager->getUserRole() === 'admin') {
            $content = '<h1>Admin Dashboard</h1>';
            $content .= '<p>Willkommen im Administrationsbereich.</p>';
            $content .= '<ul>';
            $content .= '<li><a href="/lernplattform/admin/users">Benutzerverwaltung</a></li>';
            $content .= '<li><a href="/lernplattform/admin/backup">Datenbank Backup</a></li>';
            $content .= '</ul>';
            include VIEWS_PATH . 'layout.php';
        } else {
            header("Location: /lernplattform/dashboard"); // Redirect zu normalem Dashboard, falls kein Admin
            exit();
        }
    }

    public function showUserManagement()
    {
        if ($this->sessionManager->isLoggedIn() && $this->sessionManager->getUserRole() === 'admin') {
            $userModel = new \Models\UserModel();
            $users = $userModel->getAllUsers();
            ob_start();
            include VIEWS_PATH . 'admin/users_list.php';
            $content = ob_get_clean();
            include VIEWS_PATH . 'layout.php';
        } else {
            header("Location: /lernplattform/dashboard");
            exit();
        }
    }

    public function showBackupManagement()
    {
        if ($this->sessionManager->isLoggedIn() && $this->sessionManager->getUserRole() === 'admin') {
            $content = '<h1>Datenbank Backup</h1>';
            $content .= '<p>Hier können Sie Datenbank Backups erstellen und verwalten.</p>';
            // Hier kommt später die Logik für Backups
            include VIEWS_PATH . 'layout.php';
        } else {
            header("Location: /lernplattform/dashboard");
            exit();
        }
    }
}