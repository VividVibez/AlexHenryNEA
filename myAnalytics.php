<?php
// Start the session to access session variables
session_start();

// Include necessary PHP files
include 'functions.php'; // Include functions.php file
include 'planMaker.php'; // Include planMaker.php file

// Check if the "usr" session variable is not set
if (!isset($_SESSION["usr"])) {
    header("location: login.php"); // Redirect to login.php if user is not logged in
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <!-- External CSS stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="css/accordion.css" rel="stylesheet"> <!-- Include CSS for accordion component -->
    <link href="css/nav.css" rel="stylesheet"> <!-- Include CSS for navigation -->
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
            echo "<h1>$usr</h1>"; // Display the username
          ?>
        </div>
            <div class="menu-items">
            <!-- Navigation links -->
            <li><a href="myPlan.php">My Plan</a></li>
            <li><a href="myDay.php">Today</a></li>
            <li><a href="myStopwatch.php">Stop Watch</a></li>
            <li><a href="#">Analytics</a></li>
            <li><a href="logout.php">Logout</a></li> <!-- Logout link -->
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
            $history = []; // Initialize $history as an empty array if the file doesn't exist
        }
        echo '<div class="accordion">'; // Start of accordion container

        $x = 0; // Initialize $x variable
        $y = 0; // Initialize $y variable

        do {
            $names = array_keys($history);
            $name = $names[$y]; // Get the name at index $y
            $data = $history[$name]; // Get the data associated with the name
            $z = 0; // Initialize $z variable
            $str_arr = preg_split ("/\,/", $data); // Split the data string into an array
            $accNum = "accordion-button-" . ($y+1); // Generate accordion button id

            echo "  
            <div class='accordion-item'>
                <button id='{$accNum}' aria-expanded='false'>
                <span class='accordion-title'>{$name}</span>
                <span class='icon' aria-hidden='true'></span>
                </button>
                <div class='accordion-content'><p>";

            // Loop through the data array
            foreach ($str_arr as $value) {
                $progress = "Session " . ($x+1) . ": " . $str_arr[$x]; // Generate progress text
                echo $progress . str_repeat('&nbsp;', 5); // Display progress
                
                $x++; // Increment $x
                $z++; // Increment $z

                if ($z == 8) {
                    echo "<br></br>"; // Add line break after every 8 items
                    $z = 0; // Reset $z
                }
            } 

            $x = 0; // Reset $x
            $y++; // Increment $y

            echo "</p></div></div>"; // Close accordion item

        } while ($y < count($history)); // Repeat until $y is less than the count of $history array

        echo '</div>'; // End of accordion container
        
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
