<?php

// Connection details

$host = "localhost";
$dbname = "login_db";
$user = "root";
$pword = "";


// Create connection

try {
    $mysqli = new mysqli(hostname: $host,
                         username: $user,
                         password: $pword,
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