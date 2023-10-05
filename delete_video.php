<?php
require_once "base.php";
require_once "session.php";

if (!isset($_SESSION['user_id'])) {
    // Preverite, ali je uporabnik prijavljen. Če ni, ga preusmerite na prijavo ali kam drugam.
    header("Location: login.php"); // Spremenite "login.php" na vašo prijavno stran
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Preverite, če je zahteva GET in ali je prisoten parameter "id" v URL-ju

    $videoId = $_GET["id"];
    $userId = $_SESSION['user_id'];


    $sqlCheckOwnership = "SELECT * FROM videos WHERE id_v = ? AND id_user = ?";
    $stmtCheckOwnership = $pdo->prepare($sqlCheckOwnership);
    $stmtCheckOwnership->execute([$videoId, $userId]);

    if ($stmtCheckOwnership->rowCount() > 0) {
        // Uporabnik je lastnik videoposnetka, zato lahko izbriše video
        $sqlDeleteVideo = "DELETE FROM videos WHERE id_v = ?";
        $stmtDeleteVideo = $pdo->prepare($sqlDeleteVideo);
        $stmtDeleteVideo->execute([$videoId]);

        // Po uspešnem brisanju preusmerite uporabnika nazaj na njegov kanal ali kamor želite
        header("Location: user_channel.php"); // Spremenite "user_channel.php" na ustrezno stran
        exit();
    } else {
        // Uporabnik ni lastnik videoposnetka, zato mu ne dovolite brisanja
        echo "Nimate dovoljenja za brisanje tega videoposnetka.";
    }
} else {
    // Neveljavna zahteva, lahko dodate dodatno obdelavo napak tukaj
    echo "Neveljavna zahteva.";
}
?>
