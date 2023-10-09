<?php
require_once "base.php";
require_once "session.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body >

<div class="navbar">
    <div class="logo">
        <a href="index.php"><img src="logo.png" alt="logo" class="logo" id="slikaa"></a>
    </div>
    <div class="flex">
        <nav>
            <div id="oznaka">

            </div>
            <div id="drugaoz">
                <ul>


                    <li> </li>

                </ul>
        </nav>
    </div>
</div>
<div id="stran">
    <?php


    /*
    $url = $_SERVER['REQUEST_URI'];

    $key = $_POST ['key'];

    if (isset($_GET['hotel'])) {
        $hotel = $_GET['hotel'];
        echo "<div><h1>". $hotel."</h1></div>";
    } else {
        echo "Hotel ni določen.";
    }

    */

    ?>
    <?php
    if (isset($_GET['key'])) {
        $key = $_GET['key'];

        // Time forrrrr izpisovanjeee
        echo "<h2>Rezultati iskanja za: " . $key . "</h2>";
        echo "<p>Število rezultatov vašega iskanja: ";

    } else {
        echo "<p>Please add something to .</p>";

    }

    //ZA VIDEO
    $sqle = "SELECT DISTINCT *
         FROM `videos` v
         INNER JOIN channels c ON v.id_c = c.id_c 
         WHERE UPPER(v.title) LIKE UPPER('%$key%')";

    //ZA CHANNEL
    $sqlc = "SELECT DISTINCT *
         FROM channels 
         WHERE UPPER(name_c) LIKE UPPER('%$key%')";

    //ZA PLAYLIST
    $sqlp = "SELECT DISTINCT *
         FROM playlists 
         WHERE UPPER(name) LIKE UPPER('%$key%') AND status = 'public'";


    $result=mysqli_query($link,$sqle);
    $row=mysqli_fetch_array($result);
    $t=mysqli_num_rows($result);

    $result1=mysqli_query($link,$sqlc);
    $row1=mysqli_fetch_array($result1);
    $c=mysqli_num_rows($result1);

    $result2=mysqli_query($link,$sqlp);
    $row2=mysqli_fetch_array($result2);
    $p=mysqli_num_rows($result2);

    echo $t.'</p>';
    ?>


    <form method="get" action="search.php">
        <input type="text" name="key" value="<?php echo $key?>">
        <input type="submit" value="Search">
    </form>

    <?php
    if($t == 0 && $p == 0 && $c == 0)
    {
        ?> <p>Ni rezulatata!</p><?php
    }
    else{

?>


    <button onclick="prikaziDiv(1)">Videos</button>
    <button onclick="prikaziDiv(2)">Channels</button>
    <button onclick="prikaziDiv(3)">Playlists</button>

    <div id="div1">VIDEOS
        <br>
        <?php
        if (empty($result)) {
            echo '<p>Ni rezultatov!</p>';
        } else {
            foreach ($result as $t) {
                echo '<div class="vr">';
                echo '<div class="slik">';
                echo '<div class="bes">';
                echo '<a href="page.php?id=' . urlencode($t['id_v']) . '"> ' . $t['title'] . '</a><br>';
                echo $t['title'];
                echo '<br>';
                echo $t['title'];
                echo '</div></div>';
                echo '';
                echo '';
            }
        }
        ?>
    </div>

    <div id="div2" style="display: none;">CHANNELS
        <br>
        <?php
        if (empty($result1)) {
            echo '<p>Ni rezultatov za Profil!</p>';
        } else {
            foreach ($result1 as $c) {
                echo '<div class="vr">';
                echo '<div class="slik">';
                echo '<div class="bes">';
                echo '<a href="channel.php?id=' . urlencode($c['id_c']) . '"> ' . $c['name_c'] . '</a><br>';
                echo $c['name_c'];
                echo '<br>';
                echo $c['name_c'];
                echo '</div></div>';
                echo '';
                echo '';
            }
        }
        ?>
    </div>

    <div id="div3" style="display: none;">PLAYLISTS
        <br>
        <?php
        if (empty($result2)) {
            echo '<p>Ni rezultatov za Div 3!</p>';
        } else {
            foreach ($result2 as $p) {
                echo '<div class="vr">';
                echo '<div class="slik">';
                echo '<div class="bes">';
                // Dodajte izpis za $result2
                echo '</div></div>';
                echo '';
                echo '';
            }
        }
        ?>
    </div>
    <script>
        function prikaziDiv(divToShow) {
            // Najprej skrijemo vse div elemente
            document.getElementById("div1").style.display = "none";
            document.getElementById("div2").style.display = "none";
            document.getElementById("div3").style.display = "none";

            // Nato prikažemo izbrani div
            document.getElementById("div" + divToShow).style.display = "block";
        }
    </script>

    <?php }?>



</div>

</body>
</html>
