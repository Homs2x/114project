<?php
if (isset($_POST['id'])) {
    $conn = mysqli_connect("localhost", "root", "", "ccis_connect");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM tbl_announcements WHERE id_annou='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "Announcement deleted successfully.";
    } else {
        echo "Error deleting announcement: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "No ID provided.";
}
?>
