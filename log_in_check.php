<?php
include_once 'base.php';
require_once "session.php";

$email = $_POST['email'];
$pass = $_POST['password'];

// Ensure that both email and password are provided
if (!empty($email) && !empty($pass)) {
    $query = "SELECT * FROM channels WHERE email=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();

        // Verify the password
        if (password_verify($pass, $user['password'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);

            // Store user information in the session
            $_SESSION['user_id'] = $user['id_c'];
            $_SESSION['name'] = $user['name_c'];

            header("Location: index.php");
            die();
        } else {
            // Incorrect password
            $_SESSION['error'] = "Incorrect email or password.";
        }
    } else {
        // User not found
        $_SESSION['error'] = "User not found.";
    }
} else {
    // Missing email or password
    $_SESSION['error'] = "Please enter both email and password.";
}

header("Location: login.php");
?>
