<?php
require_once "base.php";
require_once "session.php";
if (!isset($_SESSION['user_id'])) {
    // Uporabnik ni prijavljen, preusmerite ga na stran za prijavo ali kje drugje
    header("Location: log_in.php");
    exit();
}

if (isset($_GET['id'])) {
    $channelId = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Preverite, ali uporabnik obstaja v tabeli naročnikov

    $sqlCheckSubscription = "SELECT * FROM subscribers WHERE subscriber_id = ? AND account_id = ?";
    $stmtCheckSubscription = $link->prepare($sqlCheckSubscription);
    $stmtCheckSubscription->bind_param("ii", $user_id, $channelId);
    $stmtCheckSubscription->execute();
    $stmtCheckSubscription->store_result();

    if ($stmtCheckSubscription->num_rows > 0) {
        // Uporabnik je naročen, odstranite naročnino
        $sqlDeleteSubscription = "DELETE FROM subscribers WHERE subscriber_id = ? AND account_id = ?";
        $stmtDeleteSubscription = $link->prepare($sqlDeleteSubscription);
        $stmtDeleteSubscription->bind_param("ii", $user_id, $channelId);
        $stmtDeleteSubscription->execute();
    }

    // Preusmerite uporabnika nazaj na kanal
    header("Location: channel.php?id=" . $channelId);
    exit();
} else {
    // Neveljaven zahtevek, preusmerite uporabnika na primerno stran
    header("Location: neveljaven-zahtevek.php");
    exit();
}
?>
