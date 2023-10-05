<?php
require_once "base.php";

if (!isset($_POST['submit'])) {
    header("Location: reg.php");
    exit();
}

// Sanitize and validate input data
$email = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address.';
    header("Refresh: 3; url=reg.php");
    exit();
}

$name = htmlspecialchars($_POST['name']);
$surname = htmlspecialchars($_POST['surname']);
$username = htmlspecialchars($_POST['username']);
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];

// Check if passwords match
if ($password1 !== $password2) {
    echo 'Passwords do not match.';
    header("Refresh: 3; url=reg.php");
    exit();
}

// Hash the password
$hashedPassword = password_hash($password1, PASSWORD_DEFAULT);

// Check if email is already in use
$checkQuery = "SELECT COUNT(*) as count FROM channels WHERE email = ?";
$stmt_check = mysqli_prepare($link, $checkQuery);
mysqli_stmt_bind_param($stmt_check, "s", $email);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_bind_result($stmt_check, $count);
mysqli_stmt_fetch($stmt_check);
mysqli_stmt_close($stmt_check);

if ($count > 0) {
    echo 'This email is already in use.';
    header("Refresh: 3; url=reg.php");
    exit();
}

// Insert the user into the database using prepared statements
$insertQuery = "INSERT INTO `channels`(`name`, `surname`, `email`, `name_c`, `password`) 
VALUES (?, ?, ?, ?, ?)";
$stmt_insert = mysqli_prepare($link, $insertQuery);
mysqli_stmt_bind_param($stmt_insert, "sssss", $name, $surname, $email, $username, $hashedPassword);

if (mysqli_stmt_execute($stmt_insert)) {
    echo 'Registration was successful.';
    header("Location: index.php");
} else {
    echo 'Registration failed. Please try again later.';
    header("Refresh: 3; url=reg.php");
}

mysqli_stmt_close($stmt_insert);
?>
