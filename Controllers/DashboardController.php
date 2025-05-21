<?php

namespace Controllers;

class DashboardController
{
    private $sessionManager;

    public function __construct()
    {
        $this->sessionManager = new \SessionManager();
    }

    public function showDashboard()
    {
        if ($this->sessionManager->isLoggedIn()) {
            $role = $this->sessionManager->getUserRole();
            $content = '';
            switch ($role) {
                case 'admin':
                    $content = '<h1>Admin Dashboard</h1><p>Hier sehen Administratoren spezifische Informationen.</p>';
                    break;
                case 'trainer':
                    $content = '<h1>Trainer Dashboard</h1><p>Hier sehen Trainer relevante Daten.</p>';
                    break;
                case 'user':
                default:
                    $content = '<h1>Mein Dashboard</h1><p>Hier sehen Benutzer ihre Fortschritte und Schulungen.</p>';
                    break;
            }
            include VIEWS_PATH . 'layout.php';
        } else {
            header("Location: /lernplattform/login");
            exit();
        }
    }
}