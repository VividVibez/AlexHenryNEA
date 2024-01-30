<?php

// Connection details

$host = "localhost";
$dbname = "login_db";
$username = "root";
$password = "";


// Create connection

try {
    $mysqli = new mysqli(hostname: $host,
                username: $username,
                password: $password,
                database: $dbname);
}

// Check for connection errors

catch (Exception $e) {
    if ($e->getMessage() == mysqli_connect_error()) {
        // Print error message
        die($e->getMessage());
    }
}

// Return database to signup.php

return $mysqli;