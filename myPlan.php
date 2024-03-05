<?php
session_start();

include 'functions.php';
include 'planMaker.php';

if (!isset($_SESSION["usr"])) {
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href='css/navbar.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
</head>
</head>
<body>
<main class="main">
  <aside class="sidebar">
    <nav class="nav">
      <ul>
        <li class="active"><a href="#">My Plan</a></li>
        <li><a href="#">Workout</a></li>
        <li><a href="#">Statistics</a></li>
        <li><a href="#">Account</a></li>
      </ul>
    </nav>
  </aside>

  <section class="days">
    <div class="container">
      <?php
      newPlan($_SESSION["usr"]);

      
      // JSON data containing the training plan
      $json_data = newPlan($_SESSION["usr"]);

      // Decode JSON data
      $data = json_decode($json_data, true);

      // Function to generate HTML for a single training day
      function generateDayHTML($day, $exercises) {
          $html = "<h3>$day</h3><ul>";
          foreach ($exercises as $exercise) {
              $name = $exercise['name'];
              $equipment = $exercise['equipment'];
              $difficulty = $exercise['difficulty'];
              $type = $exercise['type'];
              $html .= "<li>Name: $name | Equipment: $equipment | Difficulty: $difficulty | Type: $type</li>";
          }
          $html .= "</ul>";
          return $html;
      }

      // Generate HTML for each training day
      $html = '';
      foreach ($data as $day => $exercises) {
          $html .= generateDayHTML($day, $exercises);
      }

      // Display the HTML
      echo $html;
      ?>

    </div>
  </section>
</main>
</html>