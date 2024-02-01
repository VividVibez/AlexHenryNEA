<?php

// Store username and password
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if loginUsername and loginPassword are set in $_POST
    if (isset($_POST["loginUsername"]) && isset($_POST["loginPassword"])) {
        // Store username and password after sanitizing
        $username = strtolower($_POST["loginUsername"]); // Convert username to lowercase for consistency
        $password = $_POST["loginPassword"];
    } else {
        // Handle the case where either loginUsername or loginPassword is not set
        die ("Please provide both username and password.");
    }
}

// Re-hash password for consistency with stored hashed passwords in the database
$password = password_hash($password, PASSWORD_DEFAULT);

// Function to check if user exists
function userVerify($pw, $un) {
    // Connect to database by including 'database.php'
    $conn = require __DIR__ . "/database.php";

    // Setup SQL to check if username and password are in the database
    $check = sprintf("SELECT password_hash
    FROM user
    WHERE username = '%s'",
    $conn->real_escape_string($un));

    // Check user exists
    // Run check SQL statement
    $rs = mysqli_query($conn, $check);
    // Count every time the SQL statement finds a value and store it in data array
    $data = mysqli_fetch_array($rs, MYSQLI_NUM);
    // Check if the data array has any values
    if (!$data) {
        // User doesn't exist
        return FALSE;
    } else {
        // User exists
        return TRUE;
    }
}

// Run user verification function and inform user if they are logged in or not
if (userVerify($password, $username) == TRUE) {
    // Redirect user to main page if login is successful
    header("location: main.php");
} else {
    // Display error message if username or password are incorrect
    echo ("Username or Password are incorrect");
}

?>
