<?php
        require_once "base.php";
        require_once "session.php";
        require_once "Auth.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Ztube - Index</title>
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

  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: PhotoFolio
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/photofolio-bootstrap-photography-website-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
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
              <li><a href="reg.php">Sign in </a></li>
              
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
                    echo "You aren't following any channel.";
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

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero d-flex flex-column justify-content-center align-items-center" data-aos="fade" data-aos-delay="1500">

  </section><!-- End Hero Section -->

  <main id="main" data-aos="fade" data-aos-delay="1500">

    <!-- ======= Gallery Section ======= -->
    <section id="gallery" class="gallery">
      <div class="container-fluid">

        <div class="">
          <div class="">
              <?php
              $sqle = "SELECT v.URL, v.title, c.name_c, v.id_v, v.thumb, c.id_c
             FROM videos v
             INNER JOIN channels c ON v.id_c = c.id_c
             ORDER BY RAND()  /* Dodali smo ORDER BY RAND() za naključno vrstni red */
             LIMIT 25";

              $result = mysqli_query($link, $sqle);

              if ($result) {
                  $count = 0;
                  echo '<div class="video-row">'; // Začnemo prvo vrstico
                  while ($row = mysqli_fetch_array($result)) {

                      echo "<div class='a'><a href='page.php?id=" . $row["id_v"] . "'> " . "";
                      echo "<img src='" . ($row["thumb"] ? $row["thumb"] : "thumbnails/default.png") . "' alt='Thumbnail' class='ima'/><br>";
                      echo "<a href='page.php?id=" . $row["id_v"] . "'>" . $row["title"] . "</a> <br>";
                      echo "<a href='channel.php?id=" . $row["id_c"] . "'>" . $row["name_c"] . "</a> </div>";

                      $count++;

                      if ($count % 3 == 0) {
                          echo '</div>'; // Zaključimo trenutno vrstico
                          if ($count < 12) {
                              echo '<div class="video-row">'; // Začnemo novo vrstico
                          }
                      }
                  }
                  echo '</div>'; // Zaključimo zadnjo vrstico, če ni bila zaključena
              } else {
                  echo "Napaka pri poizvedbi.";
              }
              ?>

          </div>
          </div><!-- End Gallery Item -->






  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="container">
      <div class="copyright">
          &copy; Copyright <strong><span>Bella G.</span></strong>
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/photofolio-bootstrap-photography-website-template/ -->
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