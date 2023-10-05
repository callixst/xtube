<?php
require_once "base.php";
require_once "session.php";
error_log("Received rating: " . $_POST['rating']);

error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Preveri, ali so bili vsi potrebni podatki poslani
    if (isset($_POST["rating"]) && isset($_POST["id_v"]) && isset($_POST["id_c"])) {
        // Pridobi podatke iz POST zahtevka
        $rating = intval($_POST["rating"]);
        $id_v = intval($_POST["id_v"]);
        $id_c = intval($_POST["id_c"]);

        // Preveri veljavnost ocene (npr. med 1 in 5)
        if ($rating < 1 || $rating > 5) {
            http_response_code(400); // Napaka: Neveljavna ocena
            echo json_encode(["error" => "Neveljavna ocena"]);
            exit;
        }


        // Pripravi in izvedi SQL poizvedbo za vstavljanje ocene v tabelo rating
        $stmt = $pdo->prepare("INSERT INTO ratings (rating, id_v, id_c) VALUES (:rating, :id_v, :id_c)");
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':id_v', $id_v, PDO::PARAM_INT);
        $stmt->bindParam(':id_c', $id_c, PDO::PARAM_INT);

        if ($stmt->execute()) {
            http_response_code(200); // Success
            echo json_encode(["message" => "Rating successfully inserted"]);
        } else {
            http_response_code(500); // Server error
            echo json_encode(["error" => "Error inserting rating: " . $stmt->errorInfo()[2]]);
        }


        // Zapri povezavo do podatkovne baze
        $stmt->close();
        $pdo->close();
    } else {
        http_response_code(400); // Napaka: Manjkajoči podatki
        echo json_encode(["error" => "Manjkajoči podatki"]);
    }
} else {
    http_response_code(405); // Napaka: Neveljavna metoda
    echo json_encode(["error" => "Neveljavna zahteva"]);
}

?>

