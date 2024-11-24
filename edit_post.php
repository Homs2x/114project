<?php
$conn = mysqli_connect("localhost", "root", "", "ccis_connect");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    // Check if the uploads directory exists, if not create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $uploadOk = true;

    // Retrieve the current image path from the database
    $sql = "SELECT image FROM tbl_addpost WHERE id = '$post_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $current_image = $row['image'];

    // Handle the new image upload if provided
    if (!empty($image)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Delete the old image file if it exists
            if (!empty($current_image) && file_exists($current_image)) {
                unlink($current_image);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
            $uploadOk = false;
        }
    } else {
        // If no new image is uploaded, keep the old image
        $target_file = $current_image;
    }

    if ($uploadOk) {
        $sql = "UPDATE tbl_addpost SET title='$title', content='$content', image='$target_file' WHERE id='$post_id'";
        if (mysqli_query($conn, $sql)) {
            echo "Post updated successfully!";
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
