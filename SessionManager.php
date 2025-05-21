<?php

class SessionManager
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function setUserSession($userId, $username, $role = 'user')
    {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
    }

    public function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    public function getUsername()
    {
        return $_SESSION['username'] ?? null;
    }

    public function getUserRole()
    {
        return $_SESSION['role'] ?? null;
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function destroy()
    {
        session_destroy();
    }
}