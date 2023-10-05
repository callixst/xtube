<?php
//ppopolnoma nič nismo naredili z bazo, sam prekopirala sem ga od prejšnega in boom. Baza just being baza.

//povezava na strežnik
$server='localhost';
$user='root';
$pass='';
$link=mysqli_connect($server, $user, $pass) or die('No server');

//povezava na bazo
$db="ztube";
mysqli_select_db($link,$db) or die('No base');

//določitev nabora znakov
mysqli_set_charset($link, 'utf8');

try {
    $pdo = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Napaka pri povezavi z bazo: " . $e->getMessage());
}
?>


