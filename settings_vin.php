<?php
require_once "base.php";
require_once "session.php";

if (!isset($_SESSION['user_id'])) {
    // Preverite, ali je uporabnik prijavljen. Če ni, ga preusmerite na prijavo ali kam drugam.
    header("Location: login.php"); // Spremenite "login.php" na vašo prijavno stran
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"]) && isset($_POST["description"])) {
    // Preberite podatke iz POST obrazca
    $newTitle = $_POST["title"];
    $newDescription = $_POST["description"];

    // Predpostavljam, da imate že vzpostavljeno povezavo s podatkovno bazo

    // Izvedite SQL poizvedbo za posodobitev videoposnetka v bazi podatkov
    $sqlUpdateVideo = "UPDATE videos SET title = ?, descr = ? WHERE id_v = ?";
    $stmtUpdateVideo = $pdo->prepare($sqlUpdateVideo);
    $stmtUpdateVideo->execute([$newTitle, $newDescription, $videoId]); // $videoId predstavlja ID videoposnetka, ki ga želite posodobiti

    // Preusmerite uporabnika nazaj na stran videoposnetka po urejanju
    header("Location: page.php?id=" . $videoId); // Spremenite "page.php" na ustrezno stran
    exit();
} else {
    // Neveljavna zahteva, lahko dodate dodatno obdelavo napak tukaj
    echo "Neveljavna zahteva.";
    exit();
}
?>
