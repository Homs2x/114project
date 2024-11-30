<?php
$conn = mysqli_connect("localhost", "root", "", "ccis_connect");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Validate inputs
if (!isset($_POST['poll_question']) || empty($_POST['poll_question'])) {
    die("Poll title is required.");
}

$pollTitle = htmlspecialchars($_POST['poll_question']);
$questions = $_POST['questions'] ?? [];
$suboptions = $_POST['suboptions'] ?? [];

// Insert poll title
$sql = "INSERT INTO tbl_polls (title) VALUES (?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing statement for poll title: " . $conn->error);
}

$stmt->bind_param("s", $pollTitle);

if (!$stmt->execute()) {
    die("Error executing statement for poll title: " . $stmt->error);
}

$pollId = $stmt->insert_id;
$stmt->close();

// Insert questions and sub-options
foreach ($questions as $index => $question) {
    $questionText = htmlspecialchars($question);

    // Insert question
    $sql = "INSERT INTO poll_questions (poll_id, question_text) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement for question: " . $conn->error);
    }

    $stmt->bind_param("is", $pollId, $questionText);

    if (!$stmt->execute()) {
        die("Error executing statement for question: " . $stmt->error);
    }

    $questionId = $stmt->insert_id;
    $stmt->close();

    // Insert sub-options
    foreach ($suboptions[$index] as $option) {
        $optionText = htmlspecialchars($option);

        $sql = "INSERT INTO poll_options (question_id, option_text) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement for sub-option: " . $conn->error);
        }

        $stmt->bind_param("is", $questionId, $optionText);

        if (!$stmt->execute()) {
            die("Error executing statement for sub-option: " . $stmt->error);
        }

        $stmt->close();
    }
}

echo "Poll created successfully!";
header("Location: poll.php"); // Redirect back to the index page
$conn->close();
?>
