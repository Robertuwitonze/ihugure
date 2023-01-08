<?php 

$host = "localhost";
$user = "root";
$pass = "";
$db   = "sourcecodester_exam";
$conn = null;

try {
  $conn = new PDO("mysql:host={$host};dbname={$db};",$user,$pass);
} catch (Exception $e) {
  
}


 ?>