<?php
// Function to save question data
function saveQuestion($usr, $loc, $val) {
    // Connect to the database
    $conn = require __DIR__ . "/database.php";

    // SQL to insert basic info
    $setup = "INSERT INTO basic_info (username)
    VALUE (?)";

    // SQL to check if username exists
    $check = "SELECT COUNT(*) 
    FROM basic_info 
    WHERE username = '$usr'";

    // Execute the SQL query to check the existence of username
    $rs = mysqli_query($conn, $check);
    $data = mysqli_fetch_array($rs, MYSQLI_NUM);

    // Function to save or update data
    function save($conn, $usr, $loc, $val) {
        // SQL to update basic info
        $save = "UPDATE basic_info
        SET $loc=$val
        WHERE username = '$usr'";

        // Initialize statement
        $stmt = mysqli_stmt_init($conn);

        // Prepare the statement
        if (!mysqli_stmt_prepare($stmt, $save)) {
            die(mysqli_error($conn)); // Terminate script execution and display the error message if preparation fails
        }
        
        // Execute the statement to update data
        mysqli_stmt_execute($stmt);     
    }

    // Check if the username exists
    if ($data[0] >= 1) {
        // If username exists, update data
        save($conn, $usr, $loc, $val);
    } else {
        // If username does not exist, insert basic info and update data
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $setup)) {
            die(mysqli_error($conn)); // Terminate script execution and display the error message if preparation fails
        }

        mysqli_stmt_bind_param($stmt, "s", $usr);

        // Execute the statement to store basic info
        mysqli_stmt_execute($stmt);

        // Update data
        save($conn, $usr, $loc, $val);
    }
}

// Function to verify user
function userVerify($pw, $un) {
    // Connect to the database
    $conn = require __DIR__ . "/database.php";

    // SQL to check if username and password are in the database
    $check = "SELECT *
    FROM user
    WHERE username = '$un'";

    // Run SQL query to check user existence
    $rs = mysqli_query($conn, $check);
    $row = mysqli_fetch_assoc($rs);
    if (isset($row['password_hash'])) {
        // Verify password
        $name = $row['password_hash'];
        if (password_verify($pw,$name)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

// Function to setup plan
function planSetup($un) {
    // Connect to the database
    $conn = require __DIR__ . "/database.php";

    // SQL to insert into plan table
    $setup = "INSERT INTO plan (username)
    VALUE (?)";

    // SQL to check if username exists in plan table
    $check = "SELECT COUNT(*) 
    FROM plan 
    WHERE username = '$un'";

    // Execute the SQL query to check the existence of username
    $rs = mysqli_query($conn, $check);
    $data = mysqli_fetch_array($rs, MYSQLI_NUM);

    // If username doesn't exist, insert into plan table
    if ($data[0] == 0) {
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $setup)) {
            die(mysqli_error($conn)); // Terminate script execution and display the error message if preparation fails
        }

        mysqli_stmt_bind_param($stmt, "s", $un);

        // Execute the statement to store username in plan table
        mysqli_stmt_execute($stmt);
    }
}

// Function to mark questions as done
function questionsDone($usr) {
    // Connect to the database
    $conn = require __DIR__ . "/database.php";

    // SQL to update plan table to mark questions as answered
    $save = "UPDATE plan
    SET answered=1
    WHERE username = '$usr'";

    // Initialize statement
    $stmt = mysqli_stmt_init($conn);

    // Prepare the statement
    if (!mysqli_stmt_prepare($stmt, $save)) {
        die(mysqli_error($conn)); // Terminate script execution and display the error message if preparation fails
    }

    // Execute the statement to mark questions as done
    mysqli_stmt_execute($stmt);   
}
?>
