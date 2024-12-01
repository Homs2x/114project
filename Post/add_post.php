<?php
$conn = mysqli_connect("localhost", "root", "", "ccis_connect");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    if (!empty($image)) {
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            $uploadOk = false;
        }
    }

    if ($uploadOk) {
        $sql = "INSERT INTO tbl_addpost (title, content, image) VALUES ('$title', '$content', '$target_file')";
        if (mysqli_query($conn, $sql)) {
            echo "New post created successfully!";
            header("Location: ../index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
