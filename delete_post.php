<?php
$conn = mysqli_connect("localhost", "root", "", "ccis_connect");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);

    // Get the image file name before deleting the post
    $sql = "SELECT image FROM tbl_addpost WHERE id = '$post_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $image = $row['image'];

    // Delete the post
    $sql = "DELETE FROM tbl_addpost WHERE id = '$post_id'";
    if (mysqli_query($conn, $sql)) {
        // If the post is deleted, delete the image file from the uploads folder
        if (file_exists($image)) {
            unlink($image);
        }
        echo "Post and associated image deleted successfully!";
        header("Location: index.php"); // Redirect back to the index page
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
