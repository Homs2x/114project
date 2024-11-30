<?php
if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['content'])) {
    $conn = mysqli_connect("localhost", "root", "", "ccis_connect");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize input
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Update query
    $sql = "UPDATE tbl_announcements SET title='$title', content='$content' WHERE id_annou='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "Announcement updated successfully.";
        header("Location: announcement.php"); // Redirect back to the index page
        exit();
    } else {
        echo "Error updating announcement: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Invalid input data.";
}
?>
