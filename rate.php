<?php
require_once "base.php";
require_once "session.php";

if (!isset($_SESSION['user_id'])) {
    // Preverite, ali je uporabnik prijavljen. Če ni, lahko dodate ustrezno obvladovanje napak.
    echo "Uporabnik ni prijavljen.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Preberite oceno iz AJAX zahteve
    $userRating = $_POST["rating"];
    $videoId = $_POST["video_id"]; // Preberite tudi video_id iz AJAX zahteve

    // Preverite, ali uporabnik že ocenil ta video
    $userId = $_SESSION['user_id'];

    $sqlCheckRating = "SELECT * FROM ratings WHERE id_v = ? AND id_c = ?";
    $stmtCheckRating = $pdo->prepare($sqlCheckRating);
    $stmtCheckRating->execute([$videoId, $userId]);

    if ($stmtCheckRating->rowCount() > 0) {
        // Uporabnik je že ocenil ta video, obravnavajte to situacijo po vaših željah.
        echo "This user has already rated this videp.";
    } else {
        // Uporabnik še ni ocenil tega videa, shrani oceno v tabelo 'ratings'
        $currentTime = date("Y-m-d H:i:s");

        $sqlInsertRating = "INSERT INTO ratings (rating, time_r, id_v, id_c) VALUES (?, ?, ?, ?)";
        $stmtInsertRating = $pdo->prepare($sqlInsertRating);
        $stmtInsertRating->execute([$userRating, $currentTime, $videoId, $userId]);

        // Vrnite odgovor na AJAX zahtevo
        echo "Rating saved";
    }
} else {
    // Neveljavna zahteva, lahko dodate dodatno obvladovanje napak tukaj
    echo "Neveljavna zahteva.";
}
?>
