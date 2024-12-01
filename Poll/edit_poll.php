<?php
$conn = mysqli_connect("localhost", "root", "", "ccis_connect");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_GET['id']; // Get the poll ID from the request

// Fetch poll data by ID
$pollQuery = "SELECT * FROM tbl_polls WHERE id = '$id'";
$pollResult = mysqli_query($conn, $pollQuery);
$poll = mysqli_fetch_assoc($pollResult);

$questionsQuery = "SELECT * FROM poll_questions WHERE poll_id = '$id'";
$questionsResult = mysqli_query($conn, $questionsQuery);
$questions = mysqli_fetch_all($questionsResult, MYSQLI_ASSOC);

foreach ($questions as &$question) {
    $optionsQuery = "SELECT * FROM poll_options WHERE question_id = " . $question['id'];
    $optionsResult = mysqli_query($conn, $optionsQuery);
    $options = mysqli_fetch_all($optionsResult, MYSQLI_ASSOC);
    $question['options'] = $options;
}

$poll['questions'] = $questions;

mysqli_close($conn);

echo json_encode($poll);
?>
