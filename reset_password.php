<?php
/**
 * Password Reset Script
 * This will reset the admin password to: admin123
 */

require_once 'config/database.php';

echo "<h2>Password Reset</h2>";

try {
    // Get the admin user
    $user = db_fetch_one("SELECT * FROM users WHERE username = 'admin'");
    
    if (!$user) {
        echo "<p style='color: red;'>❌ Admin user not found!</p>";
        exit;
    }
    
    echo "<h3>Current User Info:</h3>";
    echo "<p><strong>ID:</strong> {$user['id']}</p>";
    echo "<p><strong>Username:</strong> {$user['username']}</p>";
    echo "<p><strong>Email:</strong> {$user['email']}</p>";
    echo "<p><strong>Current Password Hash:</strong> " . substr($user['password'], 0, 50) . "...</p>";
    
    // Generate new password hash
    $new_password = 'admin123';
    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    echo "<hr>";
    echo "<h3>New Password Hash:</h3>";
    echo "<p>" . substr($new_hash, 0, 50) . "...</p>";
    
    // Test the new hash
    if (password_verify($new_password, $new_hash)) {
        echo "<p style='color: green;'>✅ New hash verification: SUCCESS</p>";
    } else {
        echo "<p style='color: red;'>❌ New hash verification: FAILED</p>";
    }
    
    // Update the password
    $sql = "UPDATE users SET password = ? WHERE username = 'admin'";
    $result = db_execute($sql, [$new_hash]);
    
    if ($result) {
        echo "<hr>";
        echo "<h3 style='color: green;'>✅ Password Reset Successful!</h3>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>New Password:</strong> admin123</p>";
        echo "<p><a href='admin/login.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";
        
        // Verify the update
        echo "<hr>";
        echo "<h3>Verification Test:</h3>";
        $updated_user = db_fetch_one("SELECT * FROM users WHERE username = 'admin'");
        if (password_verify('admin123', $updated_user['password'])) {
            echo "<p style='color: green;'>✅ Password verification test: PASSED</p>";
            echo "<p>You can now login with username: <strong>admin</strong> and password: <strong>admin123</strong></p>";
        } else {
            echo "<p style='color: red;'>❌ Password verification test: FAILED</p>";
            echo "<p>Something went wrong. Please try again.</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Failed to update password!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANT:</strong> Delete this file (reset_password.php) after resetting password for security!</p>";
?>
