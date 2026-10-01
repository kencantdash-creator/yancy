<?php
// Database settings for default XAMPP (user root, empty password).
const DB_HOST = 'localhost';
const DB_NAME = 'gambling_compliance';
const DB_USER = 'root';
const DB_PASS = '';

// Admin page password. Change this before you submit.
const ADMIN_PASSWORD = 'admin123';

require_once __DIR__ . '/functions.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $ex) {
            http_response_code(500);
            exit('Database connection failed. Start MySQL in XAMPP and import database.sql.');
        }
    }
    return $pdo;
}
