<?php

ob_start(); // Turns on output buffer
session_start();

$timezone = date_default_timezone_set('Europe/London');

$conn = mysqli_connect("localhost", "root", "", "linkup");

if (mysqli_connect_errno()) {
    echo "Failed to connect:  ".mysqli_connect_errno();
}


