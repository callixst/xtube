<?php
require_once "base.php";
require_once "session.php";

if (isset($_POST['add_to_playlist'])) {
    $user_id = $_SESSION['user_id'];
    $playlist_id = $_POST['playlist_id'];
    // Tukaj bi morali preveriti, ali uporabnik dejansko ima pravico za dodajanje videa v izbrani seznam predvajanja
    // Preverite, ali obstaja pravica za dodajanje videa v seznam predvajanja
    $check_permission_sql = "SELECT p.id_p FROM playlists p INNER JOIN channel_playlist cp ON p.id_p = cp.id_p WHERE p.id_p = ? AND id_c = ?";
    $check_stmt = $pdo->prepare($check_permission_sql);
    $check_stmt->execute([$playlist_id, $user_id]);
    $playlist_exists = $check_stmt->fetchColumn();

    if (!$playlist_exists) {
        echo "Nimate dovoljenja za dodajanje videa v izbrani seznam predvajanja.";
    } else {
        // Dodajte kodo za vstavljanje videa v izbrani seznam predvajanja
        $video_id = $_POST['video_id'];

        $insert_sql = "INSERT INTO playlist_video (id_p, id_v) VALUES (?, ?)";
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->execute([$playlist_id, $video_id]);

        if ($insert_stmt) {
            echo "Video je bil uspešno dodan v izbrani seznam predvajanja.";
        } else {
            echo "Napaka pri dodajanju videa v izbrani seznam predvajanja.";
        }
    }
} else {
    echo "Napaka: Nič ni bilo poslano za obdelavo.";
}
?>
