<?php

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "am_security";

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>