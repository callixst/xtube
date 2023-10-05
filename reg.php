
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ztube</title>
    <link rel="stylesheet" type="text/css" href="style1.css">


</head>
<body>
<div id='opal'> <div id='login'>
        <h1>Registration</h1>
<form method="post" action="reg_in_check.php">

Name:<br>
    <input type="text" name="name" placeholder="name" required>
    <br>
    Surname:<br>
    <input type="text" name="surname" placeholder="surname" required>
    <br>

Email:<br>
    <input type="email" name="mail" placeholder="e-mail" required>
    <br>
    Username:<br>
    <input type="text" name="username" placeholder="Username" required>
    <br>
    Password:
    <br>
    <input type="password" name="password1" placeholder="password" required>
    <br>
    <br>
    <input type="password" name="password2" placeholder="repeat password" required>
    <br>
    <input type="reset"  value="Cancel">
    <input type="submit"  value="Sign in" name="submit" >
<!--dodaj še za sign in with google and facebook -->

</form>
<a href="log_in.php">Log in</a>

</div></div>

</body>
</html>