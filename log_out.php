<?php
//session_destroy();
//header("Location:index.php");

session_start();

// Uničenje seje
session_destroy();
// Preusmeritev na prijavno stran po odjavi
header('Location: index.php');
exit();


?>
