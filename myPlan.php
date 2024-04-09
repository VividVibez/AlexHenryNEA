<?php
session_start();

include 'functions.php'; // Including functions.php for required functions
include 'planMaker.php'; // Including planMaker.php for plan creation functionality

// Redirect to login page if user is not logged in
if (!isset($_SESSION["usr"])) {
    header("location: login.php");
    exit;
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
        <li class="hide"><a href="#">My Plan</a></li>
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
          echo "<form method='post' class='addExercise'><button name='addExercise' class='add-exercise button' role='button'>Add Exercise</button></form>";
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
          echo "<div class='buttons'";
          echo "<form method='post' class='addExercise'><button name='addExercise' class='add-exercise button' role='button'>Add Exercise</button></form>";
          echo "<form method='post' class='DeleteDay'><button name='deleteDay' class='delete-day button' role='button'>Delete Day</button></form>";
          echo "</div>";
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

<section class="plan-options">
<form method="post" class="newPlan"><button name="newPlan" value="0" class="regen-plan button" role="button">Regenerate Plan</button></form>
</section>

<style>
  .plan-options {
    margin-top: 5em;
    position: relative;
  }
  .newPlan {
    width: 100px;
    height: 100px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -50px 0 0 -50px;
  }
  .buttons {
    display: flex;
    place-content: space-between;
    margin-left: 10em;
    margin-right: 10em;
  }
  .button {
  background: #FF4742;
  border: 1px solid #FF4742;
  border-radius: 6px;
  box-shadow: rgba(0, 0, 0, 0.1) 1px 2px 4px;
  box-sizing: border-box;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-block;
  font-family: nunito,roboto,proxima-nova,"proxima nova",sans-serif;
  font-size: 16px;
  font-weight: 800;
  line-height: 16px;
  min-height: 40px;
  outline: 0;
  padding: 12px 14px;
  text-align: center;
  text-rendering: geometricprecision;
  text-transform: none;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  vertical-align: middle;
}

.button:active {
  opacity: .5;
}

.delete-day:hover,
.delete-day:active {
  background-color: initial;
  background-position: 0 0;
  color: #FF4742;
}

.add-exercise:hover,
.add-exercise:active {
  background-color: initial;
  background-position: 0 0;
  color: #2fdded;
}

.add-exercise {
  background: #2fdded;
  border: 1px solid #2fbbed;
}

.regen-plan:hover,
.regen-plan:active {
  background-color: initial;
  background-position: 0 0;
  color: #e42fed;
}

.regen-plan {
  background: #e42fed;
  border: 1px solid #c72fed;
}
</style>

<?php
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['addExercise'])){
      print_r($_POST);
      echo "1";
    };
    if (isset($_POST['newPlan'])) {
      if ($_POST['newPlan'] == "0") {
        //$trainingPlan = newPlan($_SESSION["usr"]);
        $_POST['newPlan'] = 1;
      }
      print_r($_POST);
      echo "2";
    };
    if (isset($_POST['deleteDay'])) {
      print_r($_POST);
      echo "3";
    };
  }
?>
</body>
</html>

