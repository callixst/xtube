<?php
require_once "session.php";
require_once "Auth.php";
if (!isset($_SESSION['name'])) {
                    }

                else{
                    header('Location: index.php');
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ztube</title>
   <link rel="stylesheet" type="text/css" href="style1.css">


</head>
<body>

<div id="opal">
<div id="login">
<h1>Log in</h1><br>

<form method="post" action="log_in_check.php">
<div class="g_id_signin" data-type="standard"></div>

    Email:<br>
    <input type="email" name="email" placeholder="e-mail" required>
    <br>
    Password:
    <br>
    <input type="password" name="password" placeholder="password" required>
    <br><br>
    <input type="reset"  value="Cancel">
    <input type="submit"  value="Log in" name="sub" >
</form><br>
    <a href="<?= Auth::get_google_login_url() ?>" class="btn btn-primary">Google login</a><br><br>
    <a href="reg.php">Registration</a>
    <a href="<?= Auth::get_facebook_login_url() ?>" class="btn btn-primary">Facebook login</a><br>



</div>
</div>





</body>
</html>