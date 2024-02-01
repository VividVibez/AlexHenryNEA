<?php

// Connection details
$host = "localhost"; // Hostname of the database server
$dbname = "login_db"; // Name of the database to connect to
$user = "root"; // Username to authenticate with
$pword = ""; // Password to authenticate with

// Create connection
try {
    $mysqli = new mysqli(hostname: $host, // Creating a new MySQLi object with specified parameters
                         username: $user,
                         password: $pword,
                         database: $dbname);
}

// Check for connection errors
catch (Exception $e) {

    if ($e->getMessage() == mysqli_connect_error()) {

        // Print error message and terminate script execution
        die($e->getMessage());
    }
}

// Return database connection object to signup.php
return $mysqli;