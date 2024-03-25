<?php
session_start();

include 'functions.php'; // Including functions.php for required functions
include 'planMaker.php'; // Including planMaker.php for plan creation functionality

// Redirect to login page if user is not logged in
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
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="css/nav.css" rel="stylesheet"> <!-- Including navigation bar styling -->
</head>
<body>
<nav>
    <div class="navbar">
      <div class="container nav-container">
          <input class="checkbox" type="checkbox" name="" id="" />
          <div class="hamburger-lines">
            <span class="line line1"></span>
            <span class="line line2"></span>
            <span class="line line3"></span>
          </div>  
        <div class="logo">
          <?php 
            $usr = $_SESSION["usr"];
            echo "<h1>$usr</h1>"; // Displaying the username
          ?>
        </div>
        <div class="menu-items">
          <li><a href="#">My Plan</a></li>
          <li><a href="myDay.php">Today</a></li>
          <li><a href="myStopwatch.php">Stop Watch</a></li>
          <li><a href="myAnalytics.php">Analytics</a></li>
          <li><a href="logout.php">Logout</a></li>
        </div>
      </div>
    </div>
  </nav>

<section class="days">
  <div class="container">
    <?php
    $file = "plans/" . $_SESSION["usr"] . ".json";
    // Check if the training plan file exists
    if (file_exists($file)) {
      // Read the JSON file  
      $json = file_get_contents($file); 
  
      // Decode the JSON file 
      $trainingPlan = json_decode($json, true); 

    } else {
      // Create a new training plan if the file doesn't exist
      $trainingPlan = newPlan($_SESSION["usr"]);
    }

    // Loop through each day in the training plan
    foreach ($trainingPlan as $day => $activities) {
      echo "<h2>$day</h2>"; // Display the day
      // Check if the day is a rest day
      if ($activities[0] == 'Rest Day') {
          echo "<p>Rest Day</p>"; // Display message indicating a rest day
      } else {
          echo "<table>"; // Start table to display activities
          echo "<tr><th>Name</th><th>Equipment</th><th>Type</th></tr>"; // Table header
          // Loop through each activity in the day
          foreach ($activities as $activity) {
              echo "<tr>";
              echo "<td>{$activity['name']}</td>"; // Display activity name
              echo "<td>{$activity['equipment']}</td>"; // Display required equipment
              echo "<td>{$activity['type']}</td>"; // Display activity type
              echo "</tr>";
          }
          echo "</table>"; // Close table
      }
    }
    ?>
    <!-- Adding inline CSS for table styling -->
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
</html>
