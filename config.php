<?php 
$conn = new mysqli("localhost", "root", "", "academic_portal");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>