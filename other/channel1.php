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

    <!--<link rel="stylesheet" type="text/css" href="style.css"> -->


</head>
<body>
<div><?php
    if (isset($_GET['id'])) {
    $channelId = $_GET['id'];
    //echo $channelId;


//za preverit če je prijavljen
    if (!isset($_SESSION['name'])) {
                    ?> <a href="log_in.php">Log in</a> <br>
                    <a href="reg.php">Sign in</a> <?php }
    else{
        $user_id = $_SESSION['user_id'];
    if ($channelId == $user_id) {
    ?>

    <?php

    echo "<a href='edit.php?id=" . $user_id . "'> Change user details</a><br>";




    }}

                    ?><a href="log_out.php">Log out</a><br><?php
                }


        $sql = "SELECT c.name, c.surname, c.name_c, p.id_p, c.bio, p.URL 
        FROM channels c
        LEFT JOIN pictures p ON c.pf_id = p.id_p
        WHERE c.id_c = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$channelId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $name = $user['name'];
            $surname = $user['surname'];
            $channelName = $user['name_c'];
            $channelBio = $user['bio'];

            // Če je URL slike NULL, uporabite privzeto sliko ali prikažite neko sporočilo
            $profileImage = $user['URL'] ?? 'pictures/default.jpg';

            // Prikaz uporabniških podatkov in slike profila
            echo "<div id='profilee'>";
            echo "<img src='$profileImage' id='ime' alt='Profilna slika' height='200px' width='auto'><br>";
            echo "<h1> $channelName</h1><br>";
            echo "Full name: $name $surname<br>";
            echo "Description: $channelBio<br>";
            echo "</div>";




        } else {
            // Prikaz sporočila, če kanal ne obstaja
            echo "Kanal ne obstaja.";
        }



    ?>




    <div >
        <?php
        if (isset($_GET['id'])) {
            $channelId = $_GET['id'];

            if (!isset($_SESSION['user_id'])) {
                // Uporabnik ni prijavljen, ne prikaži ničesar
            } else {
                $user_id = $_SESSION['user_id'];

                if ($channelId == $user_id) {
                    // Uporabnik gleda svoj kanal, ne prikažemo gumba
                } else {
                    // Preverite, ali je trenutni uporabnik naročen na ta kanal
                    $sqlCheckSubscription = "SELECT * FROM subscribers WHERE subscriber_id = ? AND account_id = ?";
                    $stmtCheckSubscription = $pdo->prepare($sqlCheckSubscription);
                    $stmtCheckSubscription->execute([$user_id, $channelId]);
                    $isSubscribed = $stmtCheckSubscription->rowCount() > 0;

                    if ($isSubscribed) {
                        // Uporabnik je že naročen, prikažemo "unfollow" gumb
                        echo "<a href='unfollow.php?id=" . $channelId . "'>Unfollow</a><br>";
                    } else {
                        // Uporabnik ni naročen, prikažemo "follow" gumb
                        echo "<a href='follow.php?id=" . $channelId . "'>Follow</a><br>";
                    }
                }
            }
        }

        // Ostali del vaše kode tukaj...
        ?>
    </div>














                    <div id=vids>
                <h1>Videos </h1>

                        <?php

                        $sqle = "SELECT * FROM `videos` v 
        INNER JOIN channels c ON c.id_c = v.id_c 
        WHERE c.id_c = ?";
                        $stmt = $pdo->prepare($sqle);
                        $stmt->execute([$channelId]);
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $num_rows = count($rows);

                        foreach ($rows as $row) {


                            if ($num_rows == 0) {
                                echo "<div class='sli'> No Videos uploaded </div>";
                            } else {

                                echo "<div class='okno'><a href='page.php?id=" . $row["id_v"] . "'> " . "";
                                if ($channelId == $_SESSION['user_id']) {
                                    // Prikaži povezave za urejanje in brisanje samo, če je uporabnik gledal svoj kanal
                                    echo "<a href='video_setting.php'>Uredi</a>";
                                    echo "<a href='delete_video.php'>Izbriši</a>";
                                }
                                echo "<img src='" . ($row["thumb"] ? $row["thumb"] : "default.png") . "' alt='Thumbnail' id='ima'/><br>";
                                echo "<a href='page.php?id=" . $row["id_v"] . "'>" . $row["title"] . "</a> <br>";
                                echo  $row["descr"] .
                                    "</a> </div>";
                            }

                        }
                        ?>











                    </div> </div><br>




                </div>
                    <?php
?>


<a href ="upload.php">Upload videos</a>


</body>
</html>