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
          $videoId = $_GET['id'];

          // Query to retrieve the video URL based on the video ID
          $sql = "SELECT v.URL,v.title, v.id_c, c.name_c FROM videos v
         INNER JOIN channels c ON v.id_c = c.id_c WHERE id_v = $videoId";
          $result = mysqli_query($link, $sql);

          if ($result && mysqli_num_rows($result) > 0) {
              $row = mysqli_fetch_assoc($result);
              $videoURL = $row['URL'];
              $videoTitle = $row['title'];
              $idUser=$row['id_c'];
              $username = $row['name_c'];

              // $videoDesc = $row['desc'];
?>
      <div class="container">
          <div class="row gy-4 justify-content-center">
              <div class="col-lg-4" style="margin-top: 200px;">
                  <iframe width="414" height="360" src="<?php echo $videoURL; ?>" frameborder="0" allowfullscreen id="fram"></iframe>
                  <div> <h1><?php echo $videoTitle; ?></h1>
                      <h2>Description</h2>
                      <?php
                      //echo $videoDesc;
                      echo "<a href='channel.php?id=" . $idUser . "'>". $username."</a><br>";
                      ?></div></div>

              <div class="col-lg-5 content" style=" margin-left: 100px">

                  <div class="row">
                      <div style="margin-top: 10em">


                                  <h1>Rate this item:</h1> <?php $sqlAverageRating = "SELECT AVG(rating) AS average_rating FROM ratings WHERE id_v = ?";
                                  $stmtAverageRating = $pdo->prepare($sqlAverageRating);
                                  $stmtAverageRating->execute([$videoId]);

                                  if ($stmtAverageRating->rowCount() > 0) {
                                  $result = $stmtAverageRating->fetch(PDO::FETCH_ASSOC);
                                  $averageRating = $result['average_rating'];

                                  echo number_format($averageRating, 2);
                                  } else {
                                  echo "No ratings!";
                                  } ?>
                                  <br>
                                  <div class="rating" id="rating">
                                      <span data-rating="5">&#9733;</span>
                                      <span data-rating="4">&#9733;</span>
                                      <span data-rating="3">&#9733;</span>
                                      <span data-rating="2">&#9733;</span>
                                      <span data-rating="1">&#9733;</span>
                                  </div>

                                  <p>Your rating: <span id="selected-rating">0</span>/5</p>
                                  <button id="submit-rating">Submit</button>


                                  <!-- JavaScript koda -->
                                  <script>
                                      const stars = document.querySelectorAll('.rating > span');
                                      const selectedRating = document.getElementById('selected-rating');
                                      const submitButton = document.getElementById('submit-rating');
                                      let userRating = 0;

                                      stars.forEach((star) => {
                                          star.addEventListener('click', (e) => {
                                              userRating = parseInt(e.target.getAttribute('data-rating'));
                                              updateRating();
                                          });
                                      });

                                      function updateRating() {
                                          selectedRating.innerText = userRating;
                                          stars.forEach((star) => {
                                              const rating = parseInt(star.getAttribute('data-rating'));
                                              if (rating <= userRating) {
                                                  star.innerText = '\u2605'; // Filled star
                                              } else {
                                                  star.innerText = '\u2606'; // Empty star
                                              }
                                          });
                                      }
/*
                                      submitButton.addEventListener('click', () => {
                                          const xhr = new XMLHttpRequest();
                                          xhr.open('POST', 'rate.php', true);
                                          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                          xhr.onreadystatechange = function() {
                                              if (xhr.readyState === XMLHttpRequest.DONE) {
                                                  if (xhr.status === 200) {
                                                      alert(xhr.responseText); // Prikaži odgovor iz rate.php
                                                  } else {
                                                      alert('Error saving rating'); // Obravnajte napake tukaj
                                                  }
                                              }
                                          };
                                          xhr.send('rating=' + userRating);
                                      }); */


                                      // Dodajte to funkcijo, ki se bo izvedla, ko se stran naloži
                                      document.addEventListener("DOMContentLoaded", function () {
                                          const submitButton = document.getElementById('submit-rating');
                                          const videoId = <?php echo json_encode($videoId); ?>; // Dodajte to

                                          submitButton.addEventListener('click', () => {
                                              // Dodajte kodo za obdelavo klika na gumb za ocenjevanje
                                              const userRating = parseInt(document.getElementById('selected-rating').innerText);

                                              // Uporabite XMLHttpRequest za pošiljanje ocene na rate.php
                                              const xhr = new XMLHttpRequest();
                                              xhr.open('POST', 'rate.php', true);
                                              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                              xhr.onreadystatechange = function () {
                                                  if (xhr.readyState === XMLHttpRequest.DONE) {
                                                      if (xhr.status === 200) {
                                                          alert(xhr.responseText); // Prikažite odgovor iz rate.php
                                                      } else {
                                                          alert('Napaka pri shranjevanju ocene'); // Obvladujte napake tukaj
                                                      }
                                                  }
                                              };

                                              // Dodajte videoId kot parameter
                                              xhr.send('rating=' + userRating + '&video_id=' + videoId);
                                          });
                                      });
                                  </script>







                              </li>
<br><br>
                              <form method="post" action="playlist_in.php">
                                  <?php
                                  // Preverite, če je uporabnik prijavljen
                                  if (!isset($_SESSION['user_id'])) {
                                      echo "The user isnt signed in.";
                                  } else {
                                      $user_id = $_SESSION['user_id'];

                                      $sqle = "SELECT * FROM channels c INNER JOIN channel_playlist pc ON pc.id_c=c.id_c INNER JOIN playlists p ON p.id_p=pc.id_p WHERE c.id_c = ?";
                                      $stmt = $pdo->prepare($sqle);
                                      $stmt->execute([$user_id]);
                                      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                      $num_rows = count($rows);

                                      if ($num_rows == 0) {
                                          echo "No playlists";
                                      } else {
                                          ?><h3>My Playlists</h3> <?php
                                          foreach ($rows as $row) {
                                              echo "<div>";
                                              echo "<input type='radio' name='playlist_id' value='" . $row["id_p"] . "'>" . $row["name"] . "<br>";
                                              echo "</div>";
                                          }
                                          // Dodajte polje za ID videa
                                          echo "<input type='hidden' name='video_id' value='" . $videoId . "'>";
                                          echo "<button type='submit' name='add_to_playlist'>Add to playlist</button></form>";
                                      }
                                  }
                                  ?>
                          </ul>
                      </div>
                  </div>

              </div>
          </div>

      </div>

              <section id="testimonials" class="testimonials">
                  <div class="container">

                      <div class="section-header">
                          <div id="com">
                              <h2>Add a Comment</h2>

                              <?php if (!isset($_SESSION['name'])) {
                                  ?> <br>To be able to comment, you need to be signed in.<br><br> <?php }

                              else{
                                  ?>
                                  <form action="add_comment.php" method="post">
                                      <input type="hidden" name="video_id" value="<?php echo $videoId; ?>">
                                      <textarea name="comment" rows="4" cols="50" required></textarea><br>
                                      <input type="submit" value="Submit Comment">
                                  </form> <br><br>
                                  <?php
                              }
                              ?>







                              <h2>Comments</h2> <br>
                              <?php
                              // Query to retrieve comments for the specific video
                              $sqlComments = "SELECT id_co, comm, time_c, name_c FROM comments JOIN channels ON comments.id_c = channels.id_c WHERE id_v = $videoId";
                              $resultComments = mysqli_query($link, $sqlComments);

                              if ($resultComments && mysqli_num_rows($resultComments) > 0) {
                                  while ($commentRow = mysqli_fetch_assoc($resultComments)) {
                                      $commentId = $commentRow['id_co'];
                                      $comment = $commentRow['comm'];
                                      $commentTime = $commentRow['time_c'];
                                      $commenter = $commentRow['name_c'];

                                      echo "<div>";
                                      echo "<strong>$commenter</strong> - $commentTime<br>";
                                      echo "<p>$comment</p> <br>";
                                      echo "</div>";
                                  }
                              } else {
                                  echo "No comments yet.";
                              }
                              ?>
                              <!-- End of comment section -->

                              <!-- Add a form for users to submit new comments -->

                          </div>


                  </div>
              </section><!-- End Testimonials Section -->














      <?php

          } else {
              echo "Video not found.";
              exit; // Exit the script if the video is not found
          }
      } else {
          echo "Video ID not provided.";
          exit; // Exit the script if the 'id' parameter is not set
      }
      ?>

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