<?php
/**
 * Admin User Setup Script
 * Run this file once to create the admin user
 * Access: http://localhost/News/setup_admin.php
 */

require_once 'config/database.php';

echo "<h2>Admin User Setup</h2>";

try {
    // Check if users table exists
    $check = db_fetch_one("SHOW TABLES LIKE 'users'");

    if (!$check) {
        echo "<p style='color: red;'>❌ Users table doesn't exist. Please import database.sql first!</p>";
        echo "<p>Go to phpMyAdmin and import the database.sql file.</p>";
        exit;
    }

    // Check if admin user already exists
    $existing = db_fetch_one("SELECT * FROM users WHERE username = 'admin'");

    if ($existing) {
        echo "<p style='color: orange;'>⚠️ Admin user already exists!</p>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><a href='admin/login.php'>Go to Login Page</a></p>";
    } else {
        // Create admin user
        // Password: admin123
        $password_hash = password_hash('admin123', PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        db_execute($sql, ['admin', $password_hash, 'admin@example.com']);

        echo "<p style='color: green;'>✅ Admin user created successfully!</p>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><a href='admin/login.php'>Go to Login Page</a></p>";
    }

    echo "<hr>";
    echo "<h3>Database Info:</h3>";
    echo "<p><strong>Database:</strong> " . DB_NAME . "</p>";
    echo "<p><strong>Host:</strong> " . DB_HOST . "</p>";

    // Show all users
    $users = db_fetch_all("SELECT id, username, email, created_at FROM users");
    echo "<h3>Existing Users:</h3>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created At</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['username']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure you have:</p>";
    echo "<ol>";
    echo "<li>Created the database (news_cms or N9_db)</li>";
    echo "<li>Imported the database.sql file in phpMyAdmin</li>";
    echo "<li>Updated config/database.php with correct database name</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANT:</strong> Delete this file (setup_admin.php) after setup for security!</p>";
?>