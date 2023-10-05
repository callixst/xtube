<?php
session_start();

include_once './base.php';

include_once './Auth.php';

// Check if the user logged in is using Google Sign-In
if ($_GET['login_provider'] == "google") {
    $user_data = Auth::handle_google_login($_GET['code']);

    $g_id = $user_data['google_id'];
    $username = $user_data['name'];
    $email = $user_data['email'];
    //print_r($email);

    // Check if the Google ID already exists in your database (to prevent duplicates)
    $query = "SELECT * FROM channels WHERE LOWER(google_id) = LOWER(?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$g_id]);

    if ($stmt->rowCount() == 1) {
        // User already exists, log them in
        $user = $stmt->fetch();
        $_SESSION['user_id'] = $user['id_u'];
        //$_SESSION['admin'] = $user['admin'];

        // Redirect to index.php after logging in
        header("Location: index.php");
        die();
    } else {
        // User doesn't exist, so insert their information into the database
        $id_m = 1; // Your default avatar_id

        $query = "INSERT INTO channels (name_c, email, pf_id, google_id) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username, $email, $id_m, $g_id]);

        // Retrieve the newly inserted user ID
        $newUserId = $pdo->lastInsertId();

        // Log in the newly created user
        $_SESSION['user_id'] = $newUserId;

        // Redirect to index.php after inserting and logging in
        header("Location: index.php");
        die();
    }
}

// Check if the user logged in is using Facebook Sign-In
if ($_GET['login_provider'] == "facebook") {
    $user_data = Auth::handle_facebook_login($_GET['code']);
    //print_r($_GET);

    $f_id = $user_data['fb_id'];
    $username = $user_data['name'];
    $email = $user_data['email'];


    // Check if the Facebook ID already exists in your database (to prevent duplicates)
    $query = "SELECT * FROM channels WHERE LOWER(facebook_id) = LOWER(?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$f_id]);

    if ($stmt->rowCount() == 1) {
        // User already exists, log them in
        $user = $stmt->fetch();
        $_SESSION['user_id'] = $user['id_u'];
        $_SESSION['admin'] = $user['admin'];

        // Redirect to index.php after logging in
        header("Location: index.php");
        die();
    } else {
        // User doesn't exist, so insert their information into the database
        $id_m = 1; // Your default avatar_id

        $query = "INSERT INTO channels (name_c, email, pf_id, facebook_id) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username, $email, $id_m, $f_id]);

        // Retrieve the newly inserted user ID
        $newUserId = $pdo->lastInsertId();

        // Log in the newly created user
        $_SESSION['user_id'] = $newUserId;

        // Redirect to index.php after inserting and logging in
        header("Location: index.php");
        die();
    }
}
?>