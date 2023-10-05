<?php
        require_once "base.php";
        require_once "session.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ztube</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
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
                    ?><?php
                    $subscriber_id = $_SESSION['user_id']; // Postavite vaš ID naročnika

                    $sql = "SELECT c.name_c
        FROM subscribers AS s
        INNER JOIN channels AS c ON s.account_id = c.id_c
        WHERE s.subscriber_id = $subscriber_id";

                    $result1 = $link->query($sql);




                    //echo $_SESSION['name'];
                    ?> <a href="log_out.php">Log out</a> <?php
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

                    ?>
                    <a href="library.php">Library</a>
                    <a href="notification.php">Notifications</a>
    <br>
    <div id="subs">
        <h2>Following:</h2>
        <?php if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
        echo $row["name_c"] . "<br>";
        }
        } else {
        echo "Niste naročeni na noben kanal.";
        } ?>
    </div>
                     <?php }
                    ?>
                    <?php
 ?>

</div>
    <div id="subs">

    </div>

    <?php  ?>
</div>

<div id='videos'>
    <?php
    $sqle = "SELECT v.URL, v.title, c.name_c, v.id_v, v.thumb, c.id_c
             FROM videos v
             INNER JOIN channels c ON v.id_c = c.id_c
             ORDER BY RAND()  /* Dodali smo ORDER BY RAND() za naključno vrstni red */
             LIMIT 12";

    $result = mysqli_query($link, $sqle);

    if ($result) {
        $count = 0;
        echo '<div class="video-row">'; // Začnemo prvo vrstico
        while ($row = mysqli_fetch_array($result)) {
            echo "<div class='okno'><a href='page.php?id=" . $row["id_v"] . "'> " . "";
            echo "<img src='" . ($row["thumb"] ? $row["thumb"] : "thumbnails/default.png") . "' alt='Thumbnail' id='ima'/><br>";
            echo "<a href='page.php?id=" . $row["id_v"] . "'>" . $row["title"] . "</a> <br>";
            echo "<a href='channel.php?id=" . $row["id_c"] . "'>" . $row["name_c"] . "</a> </div>";

            $count++;

            if ($count % 3 == 0) {
                echo '</div>'; // Zaključimo trenutno vrstico
                if ($count < 12) {
                    echo '<div class="video-row">'; // Začnemo novo vrstico
                }
            }
        }
        echo '</div>'; // Zaključimo zadnjo vrstico, če ni bila zaključena
    } else {
        echo "Napaka pri poizvedbi.";
    }
    ?>
</div>





</body>
</html>
