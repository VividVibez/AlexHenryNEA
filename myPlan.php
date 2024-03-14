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
    <!-- <link href='css/navbar.css' rel='stylesheet'> -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="css/myPlan.css" rel="stylesheet">
</head>
</head>
<body>
<main class="main">
  <!-- <aside class="sidebar">
    <nav class="nav">
      <ul>
        <li class="active"><a href="#">My Plan</a></li>
        <li><a href="#">Workout</a></li>
        <li><a href="#">Statistics</a></li>
        <li><a href="#">Account</a></li>
      </ul>
    </nav>
  </aside> -->

  <section class="days">
    <div class="container">
      <?php
      $trainingPlan = newPlan($_SESSION["usr"]);

      foreach ($trainingPlan as $day => $activities) {
          echo "<h2>$day</h2>";
          if ($activities[0] == 'Rest Day') {
              echo "<p>Rest Day</p>";
          } else {
              echo "<table>";
              echo "<tr><th>Name</th><th>Equipment</th><th>Difficulty</th><th>Type</th></tr>";
              foreach ($activities[0] as $activity) {
                  echo "<tr>";
                  echo "<td>{$activity['name']}</td>";
                  echo "<td>{$activity['equipment']}</td>";
                  echo "<td>{$activity['difficulty']}</td>";
                  echo "<td>{$activity['type']}</td>";
                  echo "</tr>";
              }
              echo "</table>";
          }
      }
      ?>
      <style>
        table {
          width: 100%;
          border-collapse: collapse;
        }
        th, td {
          padding: 8px;
          text-align: left;
          border-bottom: 1px solid #ddd;
        }
        th {
          background-color: #f2f2f2;
        }
      </style>
    </div>
  </section>
</main>
</html>