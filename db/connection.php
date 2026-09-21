<?php

$conn = mysqli_connect("localhost", "root", "", "ecommerece");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>