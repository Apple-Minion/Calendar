
<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "calendar";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if(!$conn)
    echo "Connection error: " . mysqli_connect_error();
?>