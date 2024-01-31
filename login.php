<?php
 
// Store username 
// Store password

$username = strtolower($_POST["loginUsername"]);
$password = $_POST["loginPassword"];

// Re-hash password

$password_hash = password_hash($password, PASSWORD_DEFAULT);


// Check if user exists function

function userVerify($pw, $un) {


    // Setup sql to check if username and password are in the database

    $check = "SELECT password_hash
    FROM user
    WHERE username=$username";


    // Connect to database by calling 'database.php'

    $conn =  require __DIR__ . "/database.php";

    
    // Check user exists

    $rs = mysqli_query($conn,$check);
    $data = mysqli_fetch_array($rs, MYSQLI_NUM);

    if ($data[0] != 0) {

        // User exists
        return TRUE;

    } else {
        //User doesn't exist
        return FALSE;
    }

}


// Run user verification function and inform user if they are logged in or not

if (userVerify($password,$username) == TRUE) {

    echo ("You are logged in");

} else {

    echo ("Username or Password are incorrect");

}