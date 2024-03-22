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
    <title>Analytics</title>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="css/accordion.css" rel="stylesheet">
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
        <?php 
            $usr = $_SESSION["usr"];
            echo "<h1>$usr</h1>"; 
          ?>
        </div>
            <div class="menu-items">
            <li><a href="myPlan.php">My Plan</a></li>
            <li><a href="myDay.php">Today</a></li>
            <li><a href="myStopwatch.php">Stop Watch</a></li>
            <li><a href="#">Analytics</a></li>
            <li><a href="logout.php">Logout</a></li>
        </div>
    </div>
</div>
</nav>

<div class="container">
    <h2>My Progress</h2>
    <?php
        $file = "history/" . $_SESSION["usr"] . ".json";
        if (file_exists($file)) {
          // Read the JSON file  
          $json = file_get_contents($file); 
      
          // Decode the JSON file 
          $history = json_decode($json,true); 
    
        } else {
            $history = [];
        }
        echo '<div class="accordion">';

        $x = 0;
        $y = 0;

        do {
            $names = array_keys($history);
            $name = $names[$y];
            $data = $history[$name];
            $z = 0;
            $str_arr = preg_split ("/\,/", $data); 
            $accNum = "accordion-button-" . $y+1;

            echo "  
            <div class='accordion-item'>
                <button id='{$accNum}' aria-expanded='false'>
                <span class='accordion-title'>{$name}</span>
                <span class='icon' aria-hidden='true'></span>
                </button>
                <div class='accordion-content'><p>";

            foreach ($str_arr as $value) {
                $progress = "Session " . $x+1 . ": " . $str_arr[$x];
                echo $progress . str_repeat('&nbsp;', 5);

                $x++;
                $z++;

                if ($z == 8) {
                    echo "<br></br>";
                    $z = 0;
                }
            
            } 

            $x = 0;
            $y++;

            echo "</p></div></div>";

        } while ($y < count($history));

        echo '</div>';
        
    ?>

<script>  
const items = document.querySelectorAll(".accordion button");

function toggleAccordion() {
  const itemToggle = this.getAttribute('aria-expanded');
  
  for (i = 0; i < items.length; i++) {
    items[i].setAttribute('aria-expanded', 'false');
  }
  
  if (itemToggle == 'false') {
    this.setAttribute('aria-expanded', 'true');
  }
}

items.forEach(item => item.addEventListener('click', toggleAccordion));
</script>
</body>
</html>