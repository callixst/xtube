<?php
require_once "base.php";
require_once "session.php";
$user_id = $_SESSION['user_id'];

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

      if (!isset($_SESSION['user_id'])) {
          header("Location: log_in.php");
          exit();
      }
      $user_id = $_SESSION['user_id'];

      // Preverimo, če je podan id seznama predvajanja
      if (isset($_GET['id'])) {
          $playlist_id = $_GET['id'];
      } else {
          // Če ni podan id seznama predvajanja, preusmerimo nazaj
          header("Location: index.php");
          exit();
      }

      // Pridobimo informacije o seznamu predvajanja
      $playlist_info = getPlaylistInfo($pdo, $playlist_id);

      // Pridobimo pesmi v seznamu predvajanja
      $playlist_songs = getPlaylistSongs($pdo, $playlist_id);

      function getPlaylistInfo($pdo, $playlist_id) {
          $sql = "SELECT * FROM playlists WHERE id_p = ?";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([$playlist_id]);
          return $stmt->fetch(PDO::FETCH_ASSOC);
      }

      function getPlaylistSongs($pdo, $playlist_id) {
          $sql = "SELECT v.id_v, v.title AS song_name FROM videos v
            INNER JOIN playlist_video pv ON pv.id_v = v.id_v
            WHERE pv.id_p = ?";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([$playlist_id]);
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }

              ?>





      <?php

          ?>

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="row gy-4 justify-content-center">
          <div class="col-lg-4" style="margin-top: 200px">
              <div class="row">
                  <h2>SONGS:</h2>
                  <div style="margin-top: 20px">
                      <ul>

                          <?php echo $playlist_info['name']; ?></h1>
                          <ul>
                              <?php foreach ($playlist_songs as $song) { ?>
                                  <li><i class="bi bi-chevron-right"></i>
                                      <?php if (isset($song['song_name'])) { ?>
                                          <a href="page.php?id=<?= $song['id_v'] ?>&playlist=<?= $playlist_id ?>#play"><?= $song['song_name'] ?></a>
                                      <?php } else { ?>
                                          <span>Untitled Song</span> <!-- Provide a default value or message when 'song_name' is not present -->
                                      <?php } ?>
                                  </li>
                              <?php } ?>
                          </ul>

                      </ul>
                  </div>
              </div>

          </div>
          <div class="col-lg-5 content">


          </div>
        </div>

      </div>
    </section><!-- End About Section -->
      <?php

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

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>