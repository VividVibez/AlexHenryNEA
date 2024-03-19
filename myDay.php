<?php
session_start();

include 'functions.php';

if (!isset($_SESSION["usr"])) {
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today</title>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="css/myDay.css" rel="stylesheet">
    <link href="css/nav.css" rel="stylesheet">
    
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
                <h1>Navbar</h1>
            </div>
            <div class="menu-items">
                <li><a href="myPlan.php">My Plan</a></li>
                <li><a href="myDay.php">Today</a></li>
                <li><a href="#">Stop Watch</a></li>
                <li><a href="#">Analytics</a></li>
                <li><a href="logout.php">Logout</a></li>
            </div>
        </div>
    </div>
</nav>

<section class="today">
<div class="containter">
<?php
    $file = "plans/" . $_SESSION["usr"] . ".json";
    if (file_exists($file)) {
      // Read the JSON file  
      $json = file_get_contents($file); 
  
      // Decode the JSON file 
      $trainingPlan = json_decode($json,true); 

    } else {

      $trainingPlan = newPlan($_SESSION["usr"]);
    }

    $DayOfWeekNumber = date("w");

    // Function to find activities for a specific day
    function findActivitiesByDay($trainingPlan, $day) {
        // Check if the day exists in the data
        if (array_key_exists($day, $trainingPlan)) {
            return $trainingPlan[$day];
        } else {
            return FALSE;
        }
    }

    // Function to display activities as a table with CSS styling
    function displayActivitiesAsTable($activities) {
        echo "<table class='activities-table'>";
        echo "<tr><th>Name</th><th>Equipment</th><th>Difficulty</th><th>Type</th></tr>";
        foreach ($activities as $activity) {
            echo "<tr>";
            echo "<td>{$activity['name']}</td>";
            echo "<td>{$activity['equipment']}</td>";
            echo "<td>{$activity['difficulty']}</td>";
            echo "<td>{$activity['type']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // Test the function
    $dayToFind = "Day " . $DayOfWeekNumber;
    $activities = findActivitiesByDay($trainingPlan, $dayToFind);
    if ($activities == FALSE) {
        echo "Today is a rest day.";
    } else {
        displayActivitiesAsTable($activities);
    }

?>
<style>

    .activities-table {
        width: 100%;
        border-collapse: collapse;
    }

    .activities-table th,
    .activities-table td {
        padding: 8px;
        text-align: left;
        border: 1px solid #dddddd;
    }

    .activities-table th {
        background-color: #f2f2f2;
    }

    .activities-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>
</div>
</section>
</html>