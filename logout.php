<?php
session_start();
unset($_SESSION["usr"]);
header("location: login.php");
?>