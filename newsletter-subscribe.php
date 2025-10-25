<?php
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: /?error=invalid_email');
        exit;
    }
    
    try {
        $sql = "INSERT INTO newsletter (email) VALUES (?)";
        db_execute($sql, [$email]);
        header('Location: /?success=subscribed');
    } catch (Exception $e) {
        // Email already exists or other error
        header('Location: /?error=already_subscribed');
    }
    exit;
}

// Redirect if accessed directly
header('Location: /');
exit;
?>
