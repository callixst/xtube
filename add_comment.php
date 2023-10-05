<?php
require_once "base.php"; // Include your database connection code here
require_once "session.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $videoId = $_POST["video_id"];
    $comment = $_POST["comment"];
    $channel = $_SESSION['user_id'];


    // Insert the comment into the database
    $userId = $_SESSION["user_id"]; // Assuming you have a user session

    $sqlInsertComment = "INSERT INTO comments (comm, time_c, id_v, id_c) VALUES (?, NOW(), ?, ?)";
    $stmt = mysqli_prepare($link, $sqlInsertComment);
    mysqli_stmt_bind_param($stmt, "sii", $comment, $videoId, $userId);

    if (mysqli_stmt_execute($stmt)) {
        // Comment inserted successfully
        header("Location: page.php?id=$videoId"); // Redirect back to the video page
        exit;
    } else {
        echo "Error inserting comment: " . mysqli_error($link);
    }
} else {
    echo "Invalid request method.";
}
?>
