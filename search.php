<?php
require_once "base.php";
require_once "session.php";
$user_id = $_SESSION['user_id'];
$key = $_GET['key'];

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
                          <input type="text" name="key" value="<?php echo $key?>">
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

  <main >

      <!--changing user-->
      <?php
      if (isset($_GET['key'])) {
          $key = $_GET['key'];

          // Time forrrrr izpisovanjeee
          echo "<h2>Result for: " . $key . "</h2>";

      } else {
          echo "<p>Please add something on search.</p>";

      }

      //ZA VIDEO
      $sqle = "SELECT DISTINCT *
         FROM `videos` v
         INNER JOIN channels c ON v.id_c = c.id_c 
         WHERE UPPER(v.title) LIKE UPPER('%$key%')";

      //ZA CHANNEL
      $sqlc = "SELECT DISTINCT *
         FROM channels 
         WHERE UPPER(name_c) LIKE UPPER('%$key%')";

      //ZA PLAYLIST
      $sqlp = "SELECT DISTINCT *
         FROM playlists 
         WHERE UPPER(name) LIKE UPPER('%$key%') AND status = 'public'";


      $result=mysqli_query($link,$sqle);
      $row=mysqli_fetch_array($result);
      $t=mysqli_num_rows($result);

      $result1=mysqli_query($link,$sqlc);
      $row1=mysqli_fetch_array($result1);
      $c=mysqli_num_rows($result1);

      $result2=mysqli_query($link,$sqlp);
      $row2=mysqli_fetch_array($result2);
      $p=mysqli_num_rows($result2);

      echo $t.'</p>';
      ?>




      <?php
      if($t == 0 && $p == 0 && $c == 0)
      {
          ?> <p>No results!</p><?php
      }
      else{

          ?>

    <!-- ======= About Section ======= -->
    <section id="about" class="about">

              <div class="row" style="margin-top: 38px">
                  <h2>Results</h2>
                  <div >
                      <ul>
                          <button onclick="prikaziDiv(1)">Videos</button>
                          <button onclick="prikaziDiv(2)">Channels</button>

                          <div id="div1">
                              <br>
                              <?php
                              if (empty($result)) {
                                  echo '<p>Ni rezultatov!</p>';
                              } else {
                                  foreach ($result as $t) {
                                      echo '<div class="vr">';
                                      echo '<div class="slik">';
                                      echo '';

                                      echo "<img src='" . (!empty($row["thumb"]) ? $row["thumb"] : "thumbnails/default.png") . "' alt='Thumbnail' class='thumb'/></div>";
                                      echo "<div class='imen'><a href='page.php?id=" . $row["id_v"] . "'>" . $row["title"] . "</a> <br>";
                                      echo "<div class='imen'><a href='channel.php?id=" . $row["id_c"] . "'>" . $row["name_c"] . "</a> <br>";

                                      echo '</div></div>';
                                      echo '';
                                      echo '';
                                  }
                              }
                              ?>
                          </div>

                          <div id="div2" style="display: none;">
                              <br>
                              <?php
                              if (empty($result1)) {
                                  echo '<p>Ni rezultatov za Profil!</p>';
                              } else {
                                  foreach ($result1 as $c) {
                                      echo '<div class="vr">';
                                      echo '<div class="slik">';
                                      echo '<div class="bes">';
                                      echo '<a href="channel.php?id=' . urlencode($c['id_c']) . '"> ' . $c['name_c'] . '</a><br>';
                                      echo '<br>';
                                      echo '</div></div>';
                                      echo '';
                                      echo '';
                                  }
                              }
                              ?>
                          </div>

                    <!--      <div id="div3" style="display: none;">PLAYLISTS
                              <br>
                              <?php
                              if (empty($result2)) {
                                  echo '<p>Ni rezultatov za Div 3!</p>';
                              } else {
                                  foreach ($result2 as $p) {
                                      echo '<div class="vr">';
                                      echo '<div class="slik">';
                                      echo '<div class="bes">';
                                      // Dodajte izpis za $result2
                                      echo '</div></div>';
                                      echo '';
                                      echo '';
                                  }
                              }
                              ?>
                          </div> -->
                          <script>
                              function prikaziDiv(divToShow) {
                                  // Najprej skrijemo vse div elemente
                                  document.getElementById("div1").style.display = "none";
                                  document.getElementById("div2").style.display = "none";
                                  //document.getElementById("div3").style.display = "none";

                                  // Nato prikažemo izbrani div
                                  document.getElementById("div" + divToShow).style.display = "block";
                              }
                          </script>

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
      }
      ?>





      </div>
    </section><!-- End Testimonials Section -->

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

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>