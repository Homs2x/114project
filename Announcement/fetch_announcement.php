<?php
$conn = mysqli_connect("localhost", "root", "", "ccis_connect");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "SELECT title, content FROM tbl_announcements WHERE id_annou = $id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo json_encode(['title' => $row['title'], 'content' => $row['content']]);
        } else {
            echo json_encode(['title' => 'No title found', 'content' => 'No content found']);
        }
    } else {
        echo json_encode(['title' => 'Error', 'content' => mysqli_error($conn)]);
    }
}

mysqli_close($conn);
?>
