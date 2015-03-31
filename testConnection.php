<?php

// This is simply to test your connection to a database management server

$servername = "";
$username = "";
$password = "";

if ($servername == "" or $username == "" or $password == "") {
	echo "One of the credentials is missing";
}
else {
	// Create connection
	$conn = new mysqli($servername, $username, $password);
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

?> 