<?php
// Replace with your database credentials
$conn = new mysqli('host', 'username', 'password', 'database');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = "SELECT * FROM polls"; // Replace `polls` with your table name
$result = $conn->query($sql);

$polls = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $polls[] = $row;
    }
}
echo json_encode($polls);
?>
