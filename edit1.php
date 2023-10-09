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

<?php
if (isset($_GET['id'])) {
$channelId = $_GET['id'];
//echo $channelId;


//za preverit če je prijavljen
if (!isset($_SESSION['name'])) {
    ?> <a href="log_in.php">Log in</a> <br>
    <a href="reg.php">Sign in</a> <?php }
else{

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
        echo "<div>";
        echo "<img src='$profileImage' alt='Profilna slika' height='200px' width='auto'><br>";
        echo "<h1> $channelName</h1><br>";
        echo "Full name: $name $surname<br>";
        echo "Description: $channelBio<br>";
        echo "</div>";




    } else {
        // Prikaz sporočila, če kanal ne obstaja
        echo "Kanal ne obstaja.";
    }
        // Prikaz uporabniških podatkov in slike profila
        echo "<div>";
        ?>
    <form method="post" action="edit_in.php">

    <input type="text" id="name" name="lname" value="<?php echo $name;  ?>"><br><br>
        <input type="text" id="surname" name="lname" value="<?php echo $surname;  ?>"><br><br>
        <input type="text" id="username" name="lname" value="<?php echo $channelName;   ?>"><br><br>
        Bio: <input type="textarea" id="bio" name="lname" value="<?php echo $channelBio;   ?>"><br><br>
        <input type="submit"  value="Change" name="submit" >
    </form>
        <h3>Change your profile:</h3>
        <form action="change_pic.php" method="post" enctype="multipart/form-data">
            <input type="file" name="profile_picture" id="profile_picture">
            <input type="submit" value="Naloži sliko" name="submit">
        </form>
        <?php
        echo "<img src='$profileImage' alt='Profilna slika' height='200px' width='auto'><br>";
        echo "<h1> $channelName</h1><br>";
        echo "Full name: $name $surname<br>";
        echo "Description: $channelBio<br>";
        echo "</div>";
    }




    ?><a href="log_out.php">Log out</a><br><?php


}
?>




</head>
<body>
</body>
</html>