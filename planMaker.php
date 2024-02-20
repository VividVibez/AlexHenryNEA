<?php

// Function to retrieve exercises from a JSON file based on type
function getExercises($type) {
    $path = 'exercise_directory.json'; // Path to the JSON file
    $jsonString = file_get_contents($path); // Read the JSON file as a string
    $jsonData = json_decode($jsonString, true); // Decode the JSON string into an associative array

    $exercises = $jsonData[$type]; // Retrieve exercises based on the provided type

    return $exercises; // Return the array of exercises
}

// Function to retrieve an exercise based on type, equipment, and difficulty
function getExercise($type, $equip, $dif) {
    $exercises = getExercises($type); // Retrieve exercises of the specified type
    // Logic for retrieving an exercise based on equipment and difficulty can be added here
}

// Class definition for storing user information
class Info {
    public $f; // Focus
    public $g; // Grade
    public $d; // Days
}

// Function to retrieve user information from the database
function getInfo($un) {
    // Connect to the database
    $conn = require __DIR__ . "/database.php";

    // SQL query to retrieve user information based on username
    $check = "SELECT *
    FROM basic_info
    WHERE username = '$un'";

    // Execute the SQL query
    $rs = mysqli_query($conn, $check);

    // Fetch the row of data as an associative array
    $row = mysqli_fetch_assoc($rs);

    // Extract relevant information from the database row
    $focus = $row['focus'];
    $grade = $row['grade']; 
    $days = $row['vacancy'];

    // Create an instance of the Info class and populate it with user information
    $out = new Info(); 
    $out->f = $focus;
    $out->g = $grade;
    $out->d = $days;
    
    return $out; // Return the Info object
}

// Retrieve user information
$i = getInfo($un);
$f = $i->f; // User's focus
$g = $i->g; // User's grade
$d = $i->d; // User's available days

// Function to generate training plan for phase 1
function phase1($f, $g, $d) {

    // Phase 1: 3 Weeks of low volume strength training or technique

    // Limit the number of available days for training to 5
    if ($d > 5) {
        $d = 5;
    }

    // Determine if climbing board is available
    if ($g >= 7) {
        $board = true;
        // Determine strength training days based on user's focus
        if ($f == 1) {
            $strength_days = 3;
        } elseif ($f == 2 || $f == 3) {
            $strength_days = 2;
        } elseif ($f == 4) {
            $strength_days = 1;
        } else {
            $strength_days = 0;
        }
    } else {
        $board = false;
        // Determine strength training days based on user's focus
        if ($f == 1 || $f == 2) {
            $strength_days = 2;
        } elseif ($f == 3 || $f == 4) {
            $strength_days = 1;
        } else {
            $strength_days = 0;
        }
    }

    // Assign training routines based on the number of strength training days
    if ($strength_days == 1) {
        // 1 training day that targets climbing specific components
    } elseif ($strength_days == 2) {
        // 2 Training days that target climbing specific components and some conditioning
    } else {
        // 2 Training days that target climbing specific components 
        // 1 Day that targets conditioning and light climbing components
    }
}

?>