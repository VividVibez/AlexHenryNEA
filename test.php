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
    <title>Today</title>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="css/test.css" rel="stylesheet">
    <link href="css/nav.css" rel="stylesheet">
    <script src="test.js"></script>
    
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
                echo "<h1>$usr</h1>"; 
                ?>
            </div>
            <div class="menu-items">
                <li><a href="myPlan.php">My Plan</a></li>
                <li><a href="#">Today</a></li>
                <li><a href="myStopwatch.php">Stop Watch</a></li>
                <li><a href="myAnalytics.php">Analytics</a></li>
                <li><a href="logout.php">Logout</a></li>
            </div>
        </div>
    </div>
</nav>
<div class="col-sm-7 mx-auto">
    <header class="section-header text-center">
        <span class="h1 d-block">
        <span>❝</span>
        </span>
        <h2>Happy Customers</h2>
    </header>
</div>
  
  <div id="flex-container" class="testimonials">
    <div id="left-zone">
      <ul class="list">
        <li class="item">
          <input type="radio" id="radio_testimonial-1" name="basic_carousel" checked="checked" />
          <label class="label_testimonial-1" for="radio_testimonial-1">Diamond Pest Elimination</label>
          <div class="content-test content_testimonial-1">
            <span class="picto"></span>
            <h1>Diamond Pest Elimination</h1>
            <p>“The team really takes pride in their work. If I didn’t know any better I would think they actually worked for my company.”</p>
            <p class="testimonialFrom">Bill, Owner</p>
            <p class="testimonialState">Rochester, NY</p>
          </div>
        </li>
        <li class="item">
          <input type="radio" id="radio_testimonial-2" name="basic_carousel" />
          <label class="label_testimonial-2" for="radio_testimonial-2">A+ Handyman Service</label>
          <div class="content-test content_testimonial-2">
            <span class="picto"></span>
            <h1>A+ Handyman Service</h1>
            <p>“Quite simply… the service offers prompt response time to my visitors and helps me to better know what type of project a potential customer wants.”</p>
            <p class="testimonialFrom">Bill, Owner</p>
            <p class="testimonialState">Tucson, AZ</p>
            <br>
          </div>
        </li>
        <li class="item">
          <input type="radio" id="radio_testimonial-3" name="basic_carousel" />
          <label class="label_testimonial-3" for="radio_testimonial-3">Mod Movers</label>
          <div class="content-test content_testimonial-3">
            <span class="picto"></span>
            <h1>Mod Movers</h1>
            <p>“I couldn’t believe it. I actually had to hire someone to help me keep up with the new business. I had no idea my website had so much value.”</p>
            <p class="testimonialFrom">Marlene, Owner</p>
            <p class="testimonialState">Monterey, CA</p>
          </div>
        </li>
        <li class="item">
          <input type="radio" id="radio_testimonial-4" name="basic_carousel" />
          <label class="label_testimonial-4" for="radio_testimonial-4">AK Pest Control</label>
          <div class="content-test content_testimonial-4">
            <span class="picto"></span>
            <h1>AK Pest Control</h1>
            <p>Great company to send leads. Very efficient and pleased with the services. We get lots of leads and that whats important. Support is also great from the managers/support. Thanks YPC Chat</p>
            <p class="testimonialFrom">Mark, Owner</p>
            <p class="testimonialState">Somerset, VA</p>
            <br>
          </div>
        </li>
      </ul>
    </div>
    <div id="right-zone"></div>
  </div>
</body>
</html>