<?php
require_once "base.php";
require_once "session.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>PhotoFolio Bootstrap Template - About</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Cardo:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
      <div class="container-fluid d-flex align-items-center justify-content-between">

          <a href="index.php" class="logo d-flex align-items-center  me-auto me-lg-0">
              <img src="pictures/MINI_LOGO.png" alt="logo">
          </a>

          <nav id="navbar" class="navbar">
              <ul>
                  <li>
                      <form method="get" action="search.php">
                          <br><input type="text" name="key" placeholder="Search">
                          <input type="submit" value="Search">
                      </form></li>
                  <li><a href="index.php" class="active">Home</a></li>

                  <?php
                  if (!isset($_SESSION['name'])) {
                      ?> <li><a href="log_in.php">Log in</a></li>
                      <li><a href="reg.php">Sign in</a></li>

                  <?php }

                  else{
                      echo "<li><a href='channel.php?id=" . $_SESSION['user_id'] . "'> Channel</a></li>";
                      ?>
                      <li><a href="library.php">Library</a> </li>
                      <li><a href="log_out.php">Log out</a> </li>


                      <li class="dropdown"><a href="#"><span>Following</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                          <ul>

                              <?php
                              $subscriber_id = $_SESSION['user_id']; // Postavite vaš ID naročnika

                              $sql = "SELECT c.name_c , c.id_c
        FROM subscribers AS s
        INNER JOIN channels AS c ON s.account_id = c.id_c
        WHERE s.subscriber_id = $subscriber_id";

                              $result1 = $link->query($sql);
                              if ($result1->num_rows > 0) {
                                  while ($row = $result1->fetch_assoc()) {
                                      echo "<li><a href='channel.php?id=" . $row['id_c'] . "'> ". $row["name_c"] ."</a></li>";

                                  }
                              } else {
                                  echo "Niste naročeni na noben kanal.";
                              } ?>


                              </li>
                          </ul>



                      </li>
                      <?php

                  } ?>
              </ul>
          </nav><!-- .navbar -->

          <div class="header-social-links">

          </div>
          <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
          <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

      </div>
  </header><!-- End Header -->

  <main id="main" data-aos="fade" data-aos-delay="1500">

      <!--changing user-->
      <?php
      if (isset($_GET['id'])) {
      $channelId = $_GET['id'];


      //za preverit če je prijavljen
          $channelName = "";
          $channelBio = "";

          // Check if the user is logged in and retrieve channel information
          if (isset($_SESSION['name'])) {
              $user_id = $_SESSION['user_id'];

          }
              $sql = "SELECT c.name, c.surname, c.name_c, p.id_p, c.bio, p.URL 
                FROM channels c
                LEFT JOIN pictures p ON c.pf_id = p.id_p
                WHERE c.id_c = ?";

              $stmt = $pdo->prepare($sql);
              $stmt->execute([$channelId]);
              $user = $stmt->fetch(PDO::FETCH_ASSOC);

              if ($user) {
                  $channelName = $user['name_c'];
                  $channelBio = $user['bio'];

              }




      // Če je URL slike NULL, uporabite privzeto sliko ali prikažite neko sporočilo
      $profileImage = $user['URL'] ?? 'pictures/default.jpg';

          ?>

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="row gy-4 justify-content-center">
          <div class="col-lg-4" style="margin-top: 200px">
              <img src='<?php echo $profileImage; ?>' id='ime' alt='Profilna slika' height='200px' width='auto'><br>
          </div>
          <div class="col-lg-5 content">

            <div class="row">
                <div style="margin-top: 200px">
                <ul>
                  <li><i class="bi bi-chevron-right"></i> <strong>Channel name:</strong> <span><?php echo $channelName; ?></span></li>
                  <li><i class="bi bi-chevron-right"></i> <strong>Description:</strong> <span><?php echo $channelBio; ?></span></li>
                <?php
                if ($channelId == $user_id) {
                    echo "<li><a href='edit.php?id=" . $user_id . "'> Change user details</a></li>";
                    ?><a href ="upload.php">Upload a video</a> <?php

                }
                if (isset($_GET['id'])) {
                    $channelId = $_GET['id'];

                    if (!isset($_SESSION['user_id'])) {
                        // Uporabnik ni prijavljen, ne prikaži ničesar
                    } else {
                        $user_id = $_SESSION['user_id'];

                        if ($channelId == $user_id) {
                            // Uporabnik gleda svoj kanal, ne prikažemo gumba
                        } else {
                            // Preverite, ali je trenutni uporabnik naročen na ta kanal
                            $sqlCheckSubscription = "SELECT * FROM subscribers WHERE subscriber_id = ? AND account_id = ?";
                            $stmtCheckSubscription = $pdo->prepare($sqlCheckSubscription);
                            $stmtCheckSubscription->execute([$user_id, $channelId]);
                            $isSubscribed = $stmtCheckSubscription->rowCount() > 0;

                            if ($isSubscribed) {
                                // Uporabnik je že naročen, prikažemo "unfollow" gumb
                                echo "<li><a href='unfollow.php?id=" . $channelId . "'>Unfollow</a></li>";
                            } else {
                                // Uporabnik ni naročen, prikažemo "follow" gumb
                                echo "<li><a href='follow.php?id=" . $channelId . "'>Follow</a></li>";
                            }

                        }
                    }
                }
                ?>

                </ul>
              </div>
            </div>

          </div>
        </div>

      </div>
    </section><!-- End About Section -->
      <?php
       }?>

    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials">
      <div class="container">

        <div class="section-header">
          <p>Videos</p>
        </div>
          <?php

          $sqle = "SELECT * FROM `videos` v 
        INNER JOIN channels c ON c.id_c = v.id_c 
        WHERE c.id_c = ?";
          $stmt = $pdo->prepare($sqle);
          $stmt->execute([$channelId]);
          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $num_rows = count($rows);

          foreach ($rows as $row) {


              if ($num_rows == 0) {
                  echo "<div class='sli'> No Videos uploaded </div>";
              } else {

                  echo "<div class='testimonial-item'><a href='page.php?id=" . $row["id_v"] . "'> " . "";
                  if ($channelId == $_SESSION['user_id']) {
                      // Prikaži povezave za urejanje in brisanje samo, če je uporabnik gledal svoj kanal
                      echo "<a href='edit_v.php?id=" . $row["id_v"] . "'>Uredi</a>";
                      echo "<a href='delete_video.php?id=" . $row["id_v"] . "'>Izbriši</a>";
                  }
                  echo "<img src='" . (!empty($row["thumb"]) ? $row["thumb"] : "thumbnails/default.png") . "' alt='Thumbnail' class='thumb'/><br>";
                  echo "<a href='page.php?id=" . $row["id_v"] . "'>" . $row["title"] . "</a> <br>";
                  echo  $row["descr"] .
                      "</a> </div>";
              }

          }
          ?>


      </div>
    </section><!-- End Testimonials Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>PhotoFolio</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/photofolio-bootstrap-photography-website-template/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader">
    <div class="line"></div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>