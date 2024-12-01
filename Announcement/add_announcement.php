<?php
$conn = mysqli_connect("localhost", "root", "", "ccis_connect");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    $sql = "INSERT INTO tbl_announcements (title, content, created_at) VALUES ('$title', '$content', NOW())";
    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Announcement created successfully!');
            window.location.href = 'announcement.php';
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

