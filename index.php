<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="Photos/CCISLOGO.png" />
    <title>CCIS Connect</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="bg-light">
<!-- Header -->
<header class="text-white p-3 d-flex align-items-center justify-content-between" style="background-color: #ed5500">
    <div class="d-flex align-items-center">
        <img src="Photos/CCISLOGO.png" alt="CCIS Logo" height="40" width="40" class="mr-2" />
        <div>
            <h1 class="h5 mb-0">CCIS CONNECT</h1>
            <small>College of Computing and Information Sciences</small>
        </div>
    </div>
    <div>
        <a href="Login/Login.html" class="text-white"><button class="btn btn-primary mr-2">Logout</button></a>
    </div>
    <div class="d-none d-md-block ml-3">
        <input class="form-control rounded-pill extended-search" placeholder="Search" type="text" />
    </div>
</header>

<!-- Add a Post -->
<section class="bg-white p-3 my-3 shadow-sm text-center">
    <div class="container">
        <button id="addPostBtn" class="btn btn-primary mr-2" data-toggle="modal" data-target="#postModal">
            Add a Post
        </button>
    </div>
</section>

<!-- Modal for Adding a Post -->
<form action="add_post.php" method="post" enctype="multipart/form-data" onsubmit="updateHiddenContent()">
    <div id="postModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <h2 class="text-center mb-4">Add a New Post</h2>
                <input name="title" id="postTitle" type="text" placeholder="Enter post title" class="form-control mb-3" />
                <div class="mb-3">
                    <button type="button" onclick="formatText('bold')" class="btn btn-secondary"><b>B</b></button>
                    <button type="button" onclick="formatText('italic')" class="btn btn-secondary"><i>I</i></button>
                </div>
                <div contenteditable="true" id="postContent" class="form-control mb-3" style="min-height: 150px;"></div>
                <input type="hidden" name="content" id="hiddenContent">
                <input type="file" name="image" id="postImage" class="form-control-file mb-3" accept="image/*" />
                <img id="imagePreview" class="image-preview" style="display: none" />
                <div class="text-right">
                    <button id="closeModalBtn" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="postBtn" class="btn btn-primary" type="submit">Post</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Main Content -->
<main class="p-3">
    <div class="container">
        <div id="postContainer" class="row">
            <?php
            $conn = mysqli_connect("localhost", "root", "", "ccis_connect");

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Query to fetch posts in descending order by creation date
            $sql = "SELECT * FROM tbl_addpost ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Format the creation date
                    $formattedDate = date("F j, Y", strtotime($row['created_at']));

                    echo '<div class="col-md-4 col-sm-6 col-12 mb-4">';
                    echo '<div class="card shadow-sm" style="border-radius: 15px;">';
                    echo '<div class="card-body">';

                    // Post header with logo
                    echo '<div class="post-header d-flex align-items-center justify-content-between">';
                    $imagePath = "Photos/CCISLOGO.png"; 
                    echo '<div class="d-flex align-items-center">';
                    echo '<img src="' . $imagePath . '" style="width: 50px; height: 45px; margin-right: 15px;" />';
                    echo '<div>';
                    echo '<h5 class="mb-0">College of Computing and Information Sciences</h5>';
                    echo '<p class="mb-0">' . $formattedDate . '</p>';
                    echo '</div>';
                    echo '</div>';

                    // Delete post button
                    echo '<form action="delete_post.php" method="post" class="mb-0">';
                    echo '<input type="hidden" name="post_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" class="btn btn-danger btn-sm">';
                    echo '<i class="fas fa-trash-alt"></i>';
                    echo '</button>';
                    echo '</form>';
                    echo '</div>';

                    // Edit button - Corrected 
                    echo '<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#editModal' . $row['id'] . '" style="float:right;">'; 
                    echo '<i class="fas fa-pencil-alt"></i>'; 
                    echo '</button>';

                    // Post title and content
                    echo '<h5 class="card-title text-secondary title mt-3">' . $row['title'] . '</h5>';
                    echo '<p class="card-text">' . nl2br(string: htmlspecialchars($row['content'])) . '</p>';

                    // Validate image
                    if (!empty($row['image']) && file_exists(__DIR__ . '/' . $row['image'])) {
                        echo '<img src="' . $row['image'] . '" class="card-img-top" alt="' . '" style="width: 100%; height: auto; border-radius: 15px; margin-bottom: 15px;" />';
                    } else {
                        echo '<img src="Photos/placeholder.png" class="card-img-top" alt="Placeholder Image" style="width: 100%; height: auto; border-radius: 15px; margin-bottom: 15px;" />';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    
                    // Modal for editing post
                    echo '<div class="modal fade" id="editModal' . $row['id'] . '" tabindex="-1" aria-labelledby="editModalLabel' . $row['id'] . '" aria-hidden="true">';
                    echo '<div class="modal-dialog">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<h5 class="modal-title" id="editModalLabel' . $row['id'] . '">Edit Post</h5>';
                    echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    echo '<span aria-hidden="true">&times;</span>';
                    echo '</button>';
                    echo '</div>';
                    echo '<form action="edit_post.php" method="post" enctype="multipart/form-data">';
                    echo '<div class="modal-body">';
                    echo '<input type="hidden" name="post_id" value="' . $row['id'] . '">';
                    echo '<div class="form-group">';
                    echo '<label for="title"></label>';
                    echo '<input type="text" class="form-control" name="title" value="' . htmlspecialchars($row['title']) . '">';
                    echo '</div>';
                    echo '<div class="form-group">';
                    echo '<label for="content"></label>';
                    echo '<textarea class="form-control" name="content" rows="4">' . htmlspecialchars($row['content']) . '</textarea>';
                    echo '</div>';
                    echo '<div class="form-group">';
                    echo '<label for="image"></label>';
                    echo '<input type="file" class="form-control-file" name="image">';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                    echo '<button type="submit" class="btn btn-primary">Save changes</button>';
                    echo '</div>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
            }

            mysqli_close($conn);
            ?>
        </div>
    </div>
</main>


<!-- JavaScript -->


<!-- Bootstrap & JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="script.js">
</script>
</body>
</html>
