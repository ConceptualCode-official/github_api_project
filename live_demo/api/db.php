<?php
/**
 * Database Connection – PDO
 * Clean, Secure, Reusable
 */

$DB_HOST = "localhost";
$DB_NAME = "github_api";
$DB_USER = "root";
$DB_PASS = "";

// For portfolio / internship: NEVER expose your real DB credentials.
// Use fake/local values only.

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ]
    );
} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed",
        "info" => $e->getMessage() // (OPTIONAL) Remove in production
    ]);

    exit;
}
