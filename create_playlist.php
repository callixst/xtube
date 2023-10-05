<?php
require_once "base.php";
require_once "session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $playlist_name = $_POST["playlist_name"];
    $playlist_description = $_POST["playlist_description"];

    // Vstavite svojo SQL poizvedbo za vstavljanje novega seznama predvajanja v bazo podatkov
    $sql = "INSERT INTO playlists (id_p, name, description_p, create_p, status)
            VALUES (NULL, ?, ?, NOW(), 'active')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$playlist_name, $playlist_description]);

    // Povežite novi seznam predvajanja z uporabnikom v tabeli 'channel_playlist'
    $playlist_id = $pdo->lastInsertId();
    $insert_link_sql = "INSERT INTO channel_playlist (id_cp, id_c, id_p)
                        VALUES (NULL, ?, ?)";
    $stmt2 = $pdo->prepare($insert_link_sql);
    $stmt2->execute([$user_id, $playlist_id]);

    // Preusmerite uporabnika nazaj na stran s seznamom predvajanja ali kamorkoli želite
    header("Location: library.php");
    exit();
} else {
    // Handle invalid request (GET request or user not logged in)
    header("Location: index.php");
    exit();
}
?>

