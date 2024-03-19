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
                <li><a href="#">My Plan</a></li>
                <li><a href="myDay.php">Today</a></li>
                <li><a href="#">Stop Watch</a></li>
                <li><a href="#">Analytics</a></li>
                <li><a href="logout.php">Logout</a></li>
            </div>
        </div>
    </div>
</nav>

<section class="today">
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
?>
</section>
</html>