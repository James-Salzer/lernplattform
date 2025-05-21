<?php

require 'vendor/autoload.php';
require 'config.php';
require_once ROOT_PATH . 'SessionManager.php';
require_once ROOT_PATH . 'Database.php';
require_once MODELS_PATH . 'UserModel.php';
require_once CONTROLLERS_PATH . 'RegistrationController.php';
require_once CONTROLLERS_PATH . 'LoginController.php';
require_once CONTROLLERS_PATH . 'DashboardController.php';
require_once CONTROLLERS_PATH . 'AdminController.php'; // Neu hinzugefügt

$sessionManager = new \SessionManager();
$registrationController = new \Controllers\RegistrationController();
$loginController = new \Controllers\LoginController();
$dashboardController = new \Controllers\DashboardController();
$adminController = new \Controllers\AdminController(); // Neu hinzugefügt

$router = new \Bramus\Router\Router();

$router->get('/', function() use ($sessionManager) {
    echo '<h1>Startseite</h1>';
    echo '<br><a href="/lernplattform/register">Registrieren</a>';
    echo '<br><a href="/lernplattform/login">Einloggen</a>';
    echo '<br><a href="/lernplattform/logout">Abmelden</a>';
    if ($sessionManager->isLoggedIn()) {
        echo '<br>Eingeloggt als: ' . $sessionManager->getUsername() . ' (Rolle: ' . $sessionManager->getUserRole() . ')';
    }
});

$router->get('/register', [$registrationController, 'showRegistrationForm']);
$router->post('/register', [$registrationController, 'register']);

$router->get('/login', [$loginController, 'showLoginForm']);
$router->post('/login', [$loginController, 'login']);

$router->get('/logout', [$loginController, 'logout']);

$router->get('/dashboard', [$dashboardController, 'showDashboard']);

// Admin Routes
$router->get('/admin/dashboard', [$adminController, 'showAdminDashboard']);
$router->get('/admin/users', [$adminController, 'showUserManagement']);
$router->get('/admin/backup', [$adminController, 'showBackupManagement']);

$router->run();

?>