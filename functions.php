<?php
function saveQuestion($usr, $loc, $val) {

    $conn = require __DIR__ . "/database.php";

    $setup = "INSERT INTO basic_info (username)
    VALUE (?)";

    $check = "SELECT COUNT(*) 
    FROM basic_info 
    WHERE username = '$usr'";

    $rs = mysqli_query($conn, $check);
    $data = mysqli_fetch_array($rs, MYSQLI_NUM);

    function save($conn, $usr, $loc, $val) {

        $save = "UPDATE basic_info
        SET $loc=$val
        WHERE username = '$usr'";

        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $save)) {
            die(mysqli_error($conn)); // Terminate script execution and display the error message if preparation fails
        }

        //mysqli_stmt_bind_param($stmt, "i", $val);

        // Execute the statement to store the username and hashed password in the user table
        mysqli_stmt_execute($stmt);     
        
    }
    
    if ($data[0] >= 1) {

        save($conn, $usr, $loc, $val);

    } else {

        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $setup)) {
            die(mysqli_error($conn)); // Terminate script execution and display the error message if preparation fails
        }

        mysqli_stmt_bind_param($stmt, "s", $usr);

        // Execute the statement to store the username and hashed password in the user table
        mysqli_stmt_execute($stmt);

        save($conn, $usr, $loc, $val);
        
    }
}

// Function to check if user exists
function userVerify($pw, $un) {
    // Connect to database by including 'database.php'
    $conn = require __DIR__ . "/database.php";

    // Setup SQL to check if username and password are in the database
    $check = "SELECT 'password_hash'
    FROM user
    WHERE username = '$un'";
    // Check user exists
    // Run check SQL statement
    $rs = mysqli_query($conn, $check);
    print_r($rs);
    if (password_verify($un,$rs)) {
        
        return TRUE;
    } else {
        
        return FALSE;
    }
}
?>