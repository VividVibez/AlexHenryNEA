<?php
session_start();

include 'functions.php';
include 'planMaker.php';

if (!isset($_SESSION["usr"])) {
    header("location: login.php");
}

print_r(getInfo($_SESSION["usr"]))


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
      getInfo($_SESSION["usr"]);
      ?>
    </div>
  </section>
</main>
</html>