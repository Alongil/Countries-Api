<?php
require 'db_credentials.php';
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  $error = mysqli_connect_error();
  error_log($error);
}


