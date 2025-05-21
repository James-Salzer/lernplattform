<?php

// Debug-Flag
define('DEBUG', true); // Auf 'false' setzen, um detailliertes Logging zu deaktivieren

// Pfade
define('ROOT_PATH', __DIR__ . '/');
define('MODELS_PATH', ROOT_PATH . 'Models/');
define('VIEWS_PATH', ROOT_PATH . 'Views/');
define('CONTROLLERS_PATH', ROOT_PATH . 'Controllers/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');
define('MATERIALS_PATH', UPLOADS_PATH . 'materials/');
define('TEMPLATES_PATH', UPLOADS_PATH . 'templates/'); // Für Zertifikatsvorlagen

// Datenbankverbindung
define('DB_HOST', 'localhost'); // Dein Datenbank-Host
define('DB_USER', 'root'); // Dein Datenbank-Benutzername
define('DB_PASS', 'root'); // Dein Datenbank-Passwort
define('DB_NAME', 'lernplattform_neu'); // Dein Datenbankname

// Logging-Datei
define('LOG_FILE', ROOT_PATH . 'log.txt');

// Funktion zum Schreiben ins Log
function log_message($message) {
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[" . $timestamp . "] " . $message . "\n";
    file_put_contents(LOG_FILE, $log_entry, FILE_APPEND);
}

// Debug-Funktion
function debug_log($message) {
    if (DEBUG) {
        log_message("[DEBUG] " . $message);
    }
}

// Fehler- und Warnungs-Logging immer aktivieren
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $log_message = "[PHP Error] ($errno) $errstr in $errfile on line $errline";
    log_message($log_message);
    return true;
});

set_exception_handler(function ($exception) {
    $log_message = "[Exception] " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
    log_message($log_message);
});

?>