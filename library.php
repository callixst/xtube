<?php
        require_once "base.php";
        require_once "session.php";
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ztube</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<div>

<div id='menu'>
<!--<a href="index.php">Home</a> -->
<div id='search_bar'>
<ul>

                <li> <div id="Search">
                        <form method="get" action="search.php">
                            <br><input type="text" name="key" placeholder="Search">
                            <input type="submit" value="Search">
                        </form>
                </li>
</ul>
</div>
<!--v primeru če je oseba prijavljena -->
<div id="profil"><?php if (!isset($_SESSION['name'])) {
                    ?> <a href="log_in.php">Log in</a>
                    <a href="reg.php">Sign in</a> <?php }

                else{
                    ?><?php echo $_SESSION['name'];
                    ?> </li><li><a href="log_out.php">Log out</a><br> </li> <?php
                    ?>
                    <?php
} ?>

</div>


</div>

<div id='side_menu'>
<!--premesti vse library in subscriberje not inside -->
<div id="prijavaaa"><?php if (!isset($_SESSION['name'])) {
                    ?> For this you should be signed in <?php }

                else{
                    echo "<a href='channel.php?id=" . $_SESSION['user_id'] . "'> Channel</a>";

                    ?>               <a href="library.php">Library</a>
                    <a href="notification.php">Notifications</a>
                     <?php
                    ?>
                    <?php
} ?>
    <div id='playlist'>
        <h2>Your playlists:</h2>

        <?php
        $sqle = "SELECT * FROM channels c INNER JOIN channel_playlist pc ON pc.id_c=c.id_c INNER JOIN playlists p ON p.id_p=pc.id_p WHERE c.id_c = ?";
        $stmt = $pdo->prepare($sqle);
        $stmt->execute([$user_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $num_rows = count($rows);

        if ($num_rows == 0) {
            echo "No playlists";
        } else {
            foreach ($rows as $row) {
                echo "<div>";
                // echo  $row["name"] . " <br> " . $row["description_p"] . "<br><ul>";
                echo "<a href='playlist.php?id=" . $row["id_p"] . "'> ". $row["name"] ."</a>";
            }
        }
        ?>
    </div>

    <div >
        <form method="post" action="create_playlist.php">
            <h2>Create a New Playlist</h2>
            <label for="playlist_name">Playlist Name:</label>
            <input type="text" name="playlist_name" required>
            <label for="playlist_description">Playlist Description:</label>
            <textarea id="ame" name="playlist_description" rows="3" required></textarea>
            <input type="submit" value="Create Playlist">
        </form>
    </div>

</div></div>
</div>
<div id="main">
    <h1 id="gre">Your rated videos</h1>
</div>

<!-- -->




</body>
</html>