<?php
$username = "root"; // Replace with your MySQL username
$password = "root"; // Replace with your MySQL password
$dbname = "dcr";
$servername = "mysql_db";  // Use the hostname set in the docker-compose.yml
$port = 3306;  // MySQL port number

// Create a connection to the MySQL server without specifying a database
$conn = new mysqli($servername, $username, $password, '', $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->select_db($dbname);

$ydate = $_REQUEST['ydate'];

// Query to retrieve data from the "collection" table
$query = "SELECT * FROM collection WHERE date LIKE '%$ydate%'";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gross_yesterday = $row["gross"];
        $nett_yesterday = $row["nett"];
        $ds_yesterday = $row["distShr"];
        $es_yesterday = $row["eShr"];
    }
} else {
    $gross_yesterday = 0;
    $nett_yesterday = 0;
    $ds_yesterday = 0;
    $es_yesterday = 0;
}

$data = array("$gross_yesterday", "$nett_yesterday", "$ds_yesterday", "$es_yesterday");

// Send in JSON encoded form 
$myJSON = json_encode($data);
echo $myJSON;
?>