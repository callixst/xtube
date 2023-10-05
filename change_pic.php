<?php
require_once "base.php";
require_once "session.php";
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
    $target_dir = "pictures/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Preverite, ali je datoteka slika
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "Datoteka ni slika.";
        $uploadOk = 0;
    }

    // Preverite velikost datoteke
    if ($_FILES["profile_picture"]["size"] > 500000) {
        echo "Žal, datoteka je prevelika.";
        $uploadOk = 0;
    }

    // Dovoljeni formati datoteke (dodajte druge formate po potrebi)
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Žal, dovoljeni so le formati JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        // Preverite, ali obstaja vrstica s sliko v tabeli 'pictures' za trenutnega uporabnika
        $sqlCheck = "SELECT * FROM pictures WHERE id_p = ?";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([$user_id]);
        $pictureRow = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($pictureRow) {
            // Slika že obstaja, posodobi stolpec 'URL' v tabeli 'pictures'
            $sqlUpdate = "UPDATE pictures SET URL = ? WHERE id_p = ?";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([$target_file, $user_id]);
        } else {
            // Slika še ne obstaja, ustvarite novo vrstico v tabeli 'pictures'
            $sqlInsert = "INSERT INTO pictures (id_p, URL) VALUES (?, ?)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->execute([$user_id, $target_file]);
        }
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
           echo "moralo bi bit notr...";
        } else {
            echo "There was an error moving the file.";
        }
        // Posodobite stolpec 'pf_id' v tabeli 'channels' za trenutnega uporabnika
        $sqlChannelUpdate = "UPDATE channels SET pf_id = ? WHERE id_c = ?";
        $stmtChannelUpdate = $pdo->prepare($sqlChannelUpdate);
        $stmtChannelUpdate->execute([$user_id, $user_id]);

        echo "Slika je bila uspešno spremenjena.";
    } else {
        echo "Prišlo je do napake med nalaganjem datoteke.";
    }
}
?>
