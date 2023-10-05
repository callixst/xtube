
<?php
require_once "base.php"; // Include your database connection code here
require_once "session.php";

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $videoId = $_GET['id'];

    // Query to retrieve the video URL based on the video ID
    $sql = "SELECT v.URL,v.title, v.id_c, c.name_c FROM videos v
         INNER JOIN channels c ON v.id_c = c.id_c WHERE id_v = $videoId";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $videoURL = $row['URL'];
        $videoTitle = $row['title'];
        $idUser=$row['id_c'];
        $username = $row['name_c'];

        // $videoDesc = $row['desc'];

    } else {
        echo "Video not found.";
        exit; // Exit the script if the video is not found
    }
} else {
    echo "Video ID not provided.";
    exit; // Exit the script if the 'id' parameter is not set
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php // echo $videoTitle; ?></title>
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
    <div id="profil"><?php if (!isset($_SESSION['user_id'])) {
            ?> <a href="log_in.php">Log in</a>
            <a href="reg.php">Sign in</a> <?php }

        else{
            ?><?php echo $_SESSION['user_id'];
            ?> </li><li><a href="log_out.php">Log out</a><br> </li>
<?php
            echo "<a href='channel.php?id=" . $_SESSION['user_id'] . "'> Channel</a>";
            ?>
            <a href="library.php">Library</a>
            <a href="notification.php">Notifications</a>



            <?php
            ?>
            <?php
        } ?>

    </div>


</div>

<div id="video">
<h1><?php echo $videoTitle; ?></h1>
<iframe width="640" height="360" src="<?php echo $videoURL; ?>" frameborder="0" allowfullscreen></iframe>
    <h2>Description</h2>
    <?php
    //echo $videoDesc;
    echo "<a href='channel.php?id=" . $idUser . "'>". $username."</a><br>";


    ?>

    <a href="index.php">Index</a> <!-- Provide a link back to the homepage or wherever you want -->
</div>





<?php if (!isset($_SESSION['user_id'])) {
?>  <?php }

    else{ ?>
<div id="rating">
    <section id="ratings">
        <h2>Ratings</h2>
        <ul id="rating-list">
            <!-- Ratings will be displayed here as list items -->
        </ul>
        <!--<div id="rate-form">
            <h3>Rate this video:</h3>
            <label>
                <input type="radio" name="rating" value="like"> Like
            </label>
            <label>
                <input type="radio" name="rating" value="dislike"> Dislike
            </label>
        </div>
        <div id="likes-count"> -->
            <!-- Likes count will be displayed here -->
        </div>
    </section>
</div>
        <h1>Rate this item:</h1>
        <div class="rating" id="rating">
            <span data-rating="5">&#9733;</span>
            <span data-rating="4">&#9733;</span>
            <span data-rating="3">&#9733;</span>
            <span data-rating="2">&#9733;</span>
            <span data-rating="1">&#9733;</span>
        </div>

        <p>Your rating: <span id="selected-rating">0</span>/5</p>
        <button id="submit-rating">Submit</button>

        <script>

            const stars = document.querySelectorAll('.rating > span');
            const selectedRating = document.getElementById('selected-rating');
            const submitButton = document.getElementById('submit-rating');
            let userRating = 0;

            stars.forEach((star) => {
                star.addEventListener('click', (e) => {
                    userRating = parseInt(e.target.getAttribute('data-rating'));
                    updateRating();
                });
            });

            function updateRating() {
                selectedRating.innerText = userRating;
                stars.forEach((star) => {
                    const rating = parseInt(star.getAttribute('data-rating'));
                    if (rating <= userRating) {
                        star.innerText = '\u2605'; // Filled star
                    } else {
                        star.innerText = '\u2606'; // Empty star
                    }
                });
            }

            submitButton.addEventListener('click', () => {
                // Send the selected rating to rate.php using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'rate.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert(xhr.responseText); // Display the response from rate.php
                        } else {
                            alert('Error saving rating'); // Handle errors here
                        }
                    }
                };
                xhr.send('rating=' + userRating);
            });
        </script>


        <div id='playlist'>
            <form method="post" action="playlist_in.php">
                <?php
                // Preverite, če je uporabnik prijavljen
                if (!isset($_SESSION['user_id'])) {
                    echo "Uporabnik ni prijavljen.";
                } else {
                    $user_id = $_SESSION['user_id'];

                    $sqle = "SELECT * FROM channels c INNER JOIN channel_playlist pc ON pc.id_c=c.id_c INNER JOIN playlists p ON p.id_p=pc.id_p WHERE c.id_c = ?";
                    $stmt = $pdo->prepare($sqle);
                    $stmt->execute([$user_id]);
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $num_rows = count($rows);

                    if ($num_rows == 0) {
                        echo "No playlists";
                    } else {
                        ?><h3>My Playlists</h3> <?php
                        foreach ($rows as $row) {
                            echo "<div>";
                            echo "<input type='radio' name='playlist_id' value='" . $row["id_p"] . "'>" . $row["name"] . "<br>";
                            echo "</div>";
                        }
                        // Dodajte polje za ID videa
                        echo "<input type='hidden' name='video_id' value='" . $videoId . "'>";
                        echo "<button type='submit' name='add_to_playlist'>Add to playlist</button>";
                    }
                }
                ?>
            </form>
        </div>

 <?php }?>

<div id="com">
    <h2>Add a Comment</h2>

    <?php if (!isset($_SESSION['name'])) {
        ?> To be able to comment, you need to be signed in. <?php }

    else{
    ?>
    <form action="add_comment.php" method="post">
        <input type="hidden" name="video_id" value="<?php echo $videoId; ?>">
        <textarea name="comment" rows="4" cols="50" required></textarea><br>
        <input type="submit" value="Submit Comment">
    </form>
    <?php
    }
    ?>







<h2>Comments</h2>
<?php
// Query to retrieve comments for the specific video
$sqlComments = "SELECT id_co, comm, time_c, name_c FROM comments JOIN channels ON comments.id_c = channels.id_c WHERE id_v = $videoId";
$resultComments = mysqli_query($link, $sqlComments);

if ($resultComments && mysqli_num_rows($resultComments) > 0) {
    while ($commentRow = mysqli_fetch_assoc($resultComments)) {
        $commentId = $commentRow['id_co'];
        $comment = $commentRow['comm'];
        $commentTime = $commentRow['time_c'];
        $commenter = $commentRow['name_c'];

        echo "<div>";
        echo "<strong>$commenter</strong> - $commentTime<br>";
        echo "<p>$comment</p>";
        echo "</div>";
    }
} else {
    echo "No comments yet.";
}
?>
<!-- End of comment section -->

<!-- Add a form for users to submit new comments -->

</div>

<div class="rating">
    <input type="radio" id="star5" name="rating" value="5"><label for="star5"></label>
    <input type="radio" id="star4" name="rating" value="4"><label for="star4"></label>
    <input type="radio" id="star3" name="rating" value="3"><label for="star3"></label>
    <input type="radio" id="star2" name="rating" value="2"><label for="star2"></label>
    <input type="radio" id="star1" name="rating" value="1"><label for="star1"></label>
</div>





</body>

</html>