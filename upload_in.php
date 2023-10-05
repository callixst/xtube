<?php 

//add da se shrani trenutni datum in čas notri v bazi plus kategorijo dodaj


require_once "base.php";
require_once "session.php";

if (isset($_POST['add'])) {
    $opis = $_POST['desc'];
    $id_ch = $_SESSION['user_id'];
    $nova_slika_ime = $_FILES['video']['name'];
    $nov_title = $_POST['title'];

    $nov_desc = $_POST['desc'];

    $nova_slika_tmp = $_FILES['video']['tmp_name'];
    $nova_slika_velikost = $_FILES['video']['size'];

    // Preveri velikost slike (300 MB maksimalno)
    $maksimalna_velikost = 300 * 1024 * 1024; // 300 MB v bajtih
    if ($nova_slika_velikost > $maksimalna_velikost) {
        echo "Error: the video file is too big (max size 300 MB).";
        exit();
    }
//thumbnail
    $thumbnailName = $_FILES["thumbnail"]["name"];
    $thumbnailTmpName = $_FILES["thumbnail"]["tmp_name"];
    $thumbnailPath = "thumbnails/" . $thumbnailName;
    move_uploaded_file($thumbnailTmpName, $thumbnailPath);



    // mapa ki shranii
    $mapa = "videos/";

    // Premaknje in dodajanje
    // ...
    $new_video = $mapa . basename($nova_slika_ime);

    if (move_uploaded_file($nova_slika_tmp, $new_video)) {

        $sql = "INSERT INTO videos (URL, title, descr, id_c, thumb) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_video, $nov_title, $nov_desc, $id_ch, $thumbnailPath]);

        // Preverite, ali je poizvedba uspešna
        if ($stmt) {
            echo "Video has been added";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Error in adding a video " . implode(' ', $stmt->errorInfo()); // Izpiši specifično napako
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        echo "Error in adding a video in folder.";
        exit();
    }
// ...

}


?> 
