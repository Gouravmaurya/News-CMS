<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'N9_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL Configuration (change this if your project is in a subfolder)
define('BASE_URL', '/News');

// Create PDO connection
function db_connect()
{
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Execute query with parameters
function db_query($sql, $params = [])
{
    $pdo = db_connect();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// Fetch single row
function db_fetch_one($sql, $params = [])
{
    $stmt = db_query($sql, $params);
    return $stmt->fetch();
}

// Fetch all rows
function db_fetch_all($sql, $params = [])
{
    $stmt = db_query($sql, $params);
    return $stmt->fetchAll();
}

// Insert and return last insert ID
function db_insert($sql, $params = [])
{
    $pdo = db_connect();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $pdo->lastInsertId();
}

// Update/Delete and return affected rows
function db_execute($sql, $params = [])
{
    $stmt = db_query($sql, $params);
    return $stmt->rowCount();
}
?>