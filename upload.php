<?php
        require_once "base.php";
        require_once "session.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ztube</title>
    <link rel="stylesheet" type="text/css" href="style.css">


</head>
<body>
<div><?php if (!isset($_SESSION['name'])) {
                    ?> <a href="log_in.php">Log in</a> <br>
                    <a href="reg.php">Sign in</a> <?php }

                else{
                    ?>
                    <form method="post" action="upload_in.php" enctype="multipart/form-data">
                        <label for="video">Add the video:</label>
                        <input type="file" name="video" accept="video/*" required />
                        <label for="thumbnail">Add a thumbnail:</label>
                        <input type="file" name="thumbnail" accept="image/*" required />
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" placeholder="" required>
                        <label for="desc">Description</label>
                        <input type="text" name="desc" id="desc" placeholder="Description" required>
                        <button type="submit" name="add">Add a video</button>
                    </form>
                    
                    <?php echo $_SESSION['name'];
                    ?></li><li><a href="logout.php">Log out</a><br> </li> <?php
                    ?>
                    <?php
} ?>
</div>

</body>
</html>