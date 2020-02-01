<!DOCTYPE php>
<?php # Script 1 mysql_connect.php

// Set the database access information as constants
// define ('DB_USER', 'root');
// define ('DB_PASSWORD', '');
// define ('DB_HOST', 'localhost');
// define ('DB_NAME', 'kitamen');

// Make the connection
//$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL:' . mysqli_connect_error());
$dbc = mysqli_connect("localhost", "root", "", "kitamen");

if(mysqli_connect_errno()){
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


// Select the database
//@mysqli_select_db($dbc, DB_NAME) OR die ('Could not select the database: ' . mysqli_error($dbc));
?>