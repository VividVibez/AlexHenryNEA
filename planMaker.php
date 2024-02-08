<?php


function getInfo($un) {

    $path = 'data/movies-10.json';
    $jsonString = file_get_contents($path);
    $jsonData = json_decode($jsonString, true);
    var_dump($jsonData);
    
    // Connect to database by including 'database.php'
    $conn = require __DIR__ . "/database.php";

    // Setup SQL to check if username and password are in the database
    $check = "SELECT *
    FROM basic_info
    WHERE username = '$un'";
    // Check user exists
    // Run check SQL statement
    $rs = mysqli_query($conn, $check);
    $row = mysqli_fetch_assoc($rs);

    $focus = $row['focus'];
    $grade = $row['grade']; 
    $days = $row['availability'];
    $equipment = $row['equipment'];

    // Phase 1
    // 3 Weeks of low vol strength training or technique

    if ($days > 5) {
        $days = 5;
    }
    if (grade >=7) {
        $board = TRUE;
        if ($focus = 1) {
            $strength_days = 3;
        }
        elseif ($focus = 2 || $focus = 3)
            $strength_days = 2;

        elseif ($focus = 4){
            $strength_days = 1;
        }
        else {
            $strength_days = 0;
        }
    } 
    else {
        $board = FALSE;
        if ($focus = 1 || $focus = 2)
            $strength_days = 2;

        elseif ($focus = 3 || $focus = 4){
            $strength_days = 1;
        }
        else {
            $strength_days = 0;
        }
    }

    if ($strength_days = 1){
        // 1 training day that targets climbing specific components

    }
    elseif ($strength_days = 2) {
        // 2 Training days that target climbing specific components and some conditioning
    }
    else {
        // 2 Training days that target climbing specific components 
        // 1 Days that targets conditioning and light climbing components
    }
}

