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
                <?php 
                $usr = $_SESSION["usr"];
                echo "<h1>$usr</h1>"; 
                ?>
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
        echo "<tr><th>Name</th><th>Reps</th><th>Sets</th><th>Performance</th></tr>";

        $names = [];
        $x = 0;

        foreach ($activities as $activity) {

            $name = $activity['name'];
            $names[$x] = $name;
            $x++;

            echo "<tr>";
            echo "<td>{$name}</td>";
            echo "<td>{$activity['reps']}</td>";
            echo "<td>{$activity['sets']}</td>";
            echo "<td>";
            echo "<form class='form' method='post' novalidate>";
            echo "<div class='form__group'>
                    <input type='text' class='form__input' name='{$name}' id='{$name}' placeholder='{$activity['measurement']}' required='' />
                    <label for='name' class='form__label'>{$activity['measurement']}</label>
                </div>
                <button type='submit' class='button-34' role='button'>Complete</button>";
            echo "</form>";
            echo "</td>";        
            echo "</tr>";
        }
        
        echo "</table>";
        return $names;
    }

    // $dayToFind = "Day " . $DayOfWeekNumber;
    $dayToFind = "Day ". $DayOfWeekNumber;
    $activities = findActivitiesByDay($trainingPlan, $dayToFind);
    if ($activities == FALSE) {
        echo "Today is a rest day.";
    } else {
        $names = displayActivitiesAsTable($activities);
    }
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        foreach ($names as $name) {
            $name = str_replace(' ', '_', $name);
            
            if (isset($_POST[$name])) {
                saveHistory($_POST[$name], $name);
            }
        }
    }
    function saveHistory($value, $name) {
        $filename = ("history/" . $_SESSION["usr"] . ".json");
        
        if (file_exists($filename)) {
            // Read the JSON file  
            $json = file_get_contents($filename); 
        
            // Decode the JSON file 
            $history = json_decode($json,true);
        } else {
            
            $history = [];
        }

        $file = fopen($filename, 'w');

        if (isset($history[$name])) {
            $history[$name] = $history[$name] . "," . $value;
        } else {
            $history[$name] = $value;
        }

        $history = json_encode($history);

        fwrite($file, $history);
        fclose($file);

        
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
.button-34 {
    
    position: absolute;
    margin-left: 20rem;
    background: #5E5DF0;
    border-radius: 999px;
    box-shadow: #5E5DF0 0 10px 20px -10px;
    box-sizing: border-box;
    color: #FFFFFF;
    cursor: pointer;
    font-family: Inter,Helvetica,"Apple Color Emoji","Segoe UI Emoji",NotoColorEmoji,"Noto Color Emoji","Segoe UI Symbol","Android Emoji",EmojiSymbols,-apple-system,system-ui,"Segoe UI",Roboto,"Helvetica Neue","Noto Sans",sans-serif;
    font-size: 16px;
    font-weight: 700;
    line-height: 24px;
    opacity: 1;
    outline: 0 solid transparent;
    padding: 8px 18px;
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
    width: fit-content;
    word-break: break-word;
    border: 0;
}

.form__label {
  font-family: 'Roboto', sans-serif;
  font-size: 16px;
  margin: 0;
  display: block;
  transition: all 0.3s;
  transform: translateY(0rem);
}

.form__input {
  font-family: 'Roboto', sans-serif;
  color: #333;
  font-size: 16px;
    margin: 0;
  padding: 1rem 1.5rem;
  border-radius: 0.2rem;
  background-color: rgb(255, 255, 255);
  border: none;
  width: 80%;
  display: block;
}

.form__input:placeholder-shown + .form__label {
  opacity: 0;
  display:none;
  visibility: hidden;
  -webkit-transform: translateY(-4rem);
  transform: translateY(-4rem);
}

.form{
    display: flex;
    position: relative;
    
}
</style>
</div>
</section>
</html>
