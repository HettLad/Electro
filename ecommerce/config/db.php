<?php

$conn = new mysqli("localhost","root","","ecommerce");

if($conn->connect_error)
{
die("Database failed");
}

session_start();

include("jwt.php");

function isLoggedIn()
{
return isset($_SESSION['user_id']);
}

function isAdmin()
{
return isset($_SESSION['role']) && $_SESSION['role']=="admin";
}

?>
