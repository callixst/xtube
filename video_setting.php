<!-- video_setting.php -->

<?php
require_once "base.php";
require_once "session.php";

if (!isset($_SESSION['user_id'])) {
    // Preverite, ali je uporabnik prijavljen. Če ni, ga preusmerite na prijavo ali kam drugam.
    header("Location: login.php"); // Spremenite "login.php" na vašo prijavno stran
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Preverite, ali je zahteva GET in ali je prisoten parameter "id" v URL-ju

    $videoId = $_GET["id"];
    $userId = $_SESSION['user_id'];

    // Povežite se z bazo podatkov (predpostavljam, da imate že vzpostavljeno povezavo s podatkovno bazo)

    // Izvedite SQL poizvedbo za preverjanje, ali je uporabnik lastnik videoposnetka
    $sqlCheckOwnership = "SELECT * FROM videos WHERE id_v = ? AND id_user = ?";
    $stmtCheckOwnership = $pdo->prepare($sqlCheckOwnership);
    $stmtCheckOwnership->execute([$videoId, $userId]);

    if ($stmtCheckOwnership->rowCount() > 0) {
        // Uporabnik je lastnik videoposnetka, lahko ureja
        // Preberite trenutne podatke o videoposnetku
        $sqlGetVideoData = "SELECT * FROM videos WHERE id_v = ?";
        $stmtGetVideoData = $pdo->prepare($sqlGetVideoData);
        $stmtGetVideoData->execute([$videoId]);
        $videoData = $stmtGetVideoData->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obrazec je bil oddan, posodobite podatke o videoposnetku
            $newTitle = $_POST["title"];
            $newDescription = $_POST["description"];

            // Tukaj lahko dodate kodo za posodobitev videoposnetka v bazi podatkov z uporabo UPDATE poizvedbe
            // Predlagam, da uporabite pripravljeno izjavo za to
            // Po posodobitvi preusmerite uporabnika nazaj na stran videoposnetka

            // Primer izjave za posodobitev
            $sqlUpdateVideo = "UPDATE videos SET title = ?, descr = ? WHERE id_v = ?";
            $stmtUpdateVideo = $pdo->prepare($sqlUpdateVideo);
            $stmtUpdateVideo->execute([$newTitle, $newDescription, $videoId]);

            // Preusmerite uporabnika nazaj na stran videoposnetka po urejanju
            header("Location: page.php?id=" . $videoId);
            exit();
        }
    } else {
        // Uporabnik ni lastnik videoposnetka, zato mu ne dovolite urejanja
        echo "Nimate dovoljenja za urejanje tega videoposnetka.";
        exit();
    }
} else {
    // Neveljavna zahteva, lahko dodate dodatno obdelavo napak tukaj
    echo "Neveljavna zahteva.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Uredi videoposnetek</title>
</head>
<body>
<h1>Uredi videoposnetek</h1>
<form method="POST" action="settings_vin.php">
    <label for="title">Naslov:</label>
    <input type="text" id="title" name="title" value="<?php echo $videoData['title']; ?>"><br>

    <label for="description">Opis:</label>
    <textarea id="description" name="description"><?php echo $videoData['descr']; ?></textarea><br>

    <input type="submit" value="Shrani spremembe">
</form>
</body>
</html>
