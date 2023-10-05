<?php
require_once "base.php";
require_once "session.php";

// Preverimo, če je uporabnik prijavljen
if (!isset($_SESSION['user_id'])) {
    header("Location: log_in.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Preverimo, če je podan id seznama predvajanja
if (isset($_GET['id'])) {
    $playlist_id = $_GET['id'];
} else {
    // Če ni podan id seznama predvajanja, preusmerimo nazaj
    header("Location: index.php");
    exit();
}

// Pridobimo informacije o seznamu predvajanja
$playlist_info = getPlaylistInfo($pdo, $playlist_id);

// Pridobimo pesmi v seznamu predvajanja
$playlist_songs = getPlaylistSongs($pdo, $playlist_id);

function getPlaylistInfo($pdo, $playlist_id) {
    $sql = "SELECT * FROM playlists WHERE id_p = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$playlist_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPlaylistSongs($pdo, $playlist_id) {
    $sql = "SELECT v.id_v, v.title AS song_name FROM videos v
            INNER JOIN playlist_video pv ON pv.id_v = v.id_v
            WHERE pv.id_p = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$playlist_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Playlist</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="menu">
    <div id="profil">
        <?php if (isset($_SESSION['name'])) { ?>
            <?php echo $_SESSION['name']; ?>
            <a href="log_out.php">Log out</a><br>
        <?php } ?>
    </div>
</div>
<div id="playlist">
    <h1>Playlist: <?php echo $playlist_info['name']; ?></h1>
    <ul>
        <?php foreach ($playlist_songs as $song) { ?>
            <li>
                <?php
                // Preverite, ali je ključ 'title' dejansko prisoten v podatkih o pesmi
                if (isset($song['title'])) {
                    echo '<a href="page.php?id=' . $song['id_v'] . '&playlist=' . $playlist_id . '#play">';
                    echo $song['title'];
                    echo '</a>';
                } else {
                }



                ?>
            </li>
            <?php
            foreach ($playlist_songs as $song) { ?>
                <li>
                    <a href="page.php?id=<?php echo $song['id_v']; ?>&playlist=<?php echo $playlist_id; ?>#play">
                        <?php
                        if (isset($song['song_name'])) {
                            echo $song['song_name']; // Uporabite 'song_name' namesto 'title'
                        } else {
                            print_r($song);
                        }
                        ?>
                    </a>
                </li>
            <?php } ?>

            </a>
        </li>
        <?php } ?>



    </ul>
</div>
</body>
</html>
