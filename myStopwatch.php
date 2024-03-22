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
    <title>Stopwatch</title>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="css/stopwatch.css" rel="stylesheet">
    <link href="css/nav.css" rel="stylesheet">
    <script src="stopwatch.js"></script>
</head>
<body>
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
                echo "<h1>$usr</h1>"; 
                ?>
            </div>
            <div class="menu-items">
                <li><a href="myPlan.php">My Plan</a></li>
                <li><a href="myDay.php">Today</a></li>
                <li><a href="#">Stop Watch</a></li>
                <li><a href="myAnalytics.php">Analytics</a></li>
                <li><a href="logout.php">Logout</a></li>
            </div>
        </div>
    </div>
</nav>
<div class="wrapper">
    <h1>Stopwatch</h1>
    <p><span id="seconds">00</span>:<span id="tens">00</span></p>
    <button id="button-start">Start</button>
    <button id="button-stop">Stop</button>
    <button id="button-reset">Reset</button>
</div>   
</body>
</html>
