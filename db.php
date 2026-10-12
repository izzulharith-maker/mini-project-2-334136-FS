<?php
$conn = mysqli_connect("localhost", "root", "", "assignment_system");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>