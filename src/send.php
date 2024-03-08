<?php
$username = "root";
$password = "root";
$dbname = "dcr";
$servername = "mysql_db";  // Use the hostname set in the docker-compose.yml
$port = 3306;  // MySQL port number

// Create a connection to the MySQL server without specifying a database
$conn = new mysqli($servername, $username, $password, '', $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "CREATE DATABASE IF NOT EXISTS dcr";
$conn->query($query);

$conn->select_db($dbname);

$query = "CREATE TABLE IF NOT EXISTS collection (
        date DATE PRIMARY KEY,
        gross VARCHAR(255) NOT NULL,
        nett VARCHAR(255) NOT NULL,
        distShr VARCHAR(255) NOT NULL,
        eShr VARCHAR(255) NOT NULL
    )";
$conn->query($query);

$yestDate = $_REQUEST['yestDate'];

// Query to retrieve data from the "collection" table
$query = "SELECT * FROM collection WHERE date LIKE '%$yestDate%'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gross = $row["gross"];
        $nett = $row["nett"];
        $distShr = $row["distShr"];
        $eShr = $row["eShr"];
    }
} else {
    $gross = 0;
    $nett = 0;
    $distShr = 0;
    $eShr = 0;
}

$data = array("$gross", "$nett", "$distShr", "$eShr");

// Send in JSON encoded form 
$myJSON = json_encode($data);
echo $myJSON;

?>
