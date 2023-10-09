<?php
require_once "base.php";
require_once "session.php";

// Preverite, ali je uporabnik prijavljen
if (!isset($_SESSION['name'])) {
    // Uporabnik ni prijavljen, preusmerite ga na stran za prijavo
    header("Location: log_in.php");
    exit();
}

// Preverite, ali je obrazec za urejanje podatkov poslan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Preverite, ali so vsi obvezni podatki prisotni v obrazcu
    if (isset($_POST["lname"]) && isset($_POST["bio"])) {
        $newChannelName = $_POST["lname"];
        $newBio = $_POST["bio"];
        $userId = $_SESSION['user_id']; // ID trenutno prijavljenega uporabnika

        // Posodobite podatke v bazi
        $sql = "UPDATE channels SET name_c = ?, bio = ? WHERE id_c = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newChannelName, $newBio, $userId]);

        // Preusmerite uporabnika nazaj na stran About s sporočilom o uspehu
        header("Location: index.php");
        exit();
    }
}

// Če obrazec ni poslan ali manjkajo podatki, se preusmeri nazaj na stran About s sporočilom o napaki
header("Location: about.php?error=1");
exit();
?>
