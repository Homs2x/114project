<?php
// Check for a success message in the URL
$successMessage = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
?>
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
<!-- Success Notification Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <?php echo $successMessage; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

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
        <a href="Login/Login.html" class="text-white"><button class="btn btn-primary mr-2" style="background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">Logout</button></a>
    </div>
    <div class="d-none d-md-block ml-3">
        <input class="form-control rounded-pill extended-search" placeholder="Search" type="text" />
    </div>
</header>

<!-- Add a Post -->
<section class="bg-white p-3 my-3 shadow-sm text-center">
    <div class="container">
        <button id="addPostBtn" class="btn btn-primary mr-2" data-toggle="modal" data-target="#postModal" style="background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">
            Create a News
        </button>
        <button id="addPollBtn" class="btn btn-primary" data-toggle="modal" data-target="#pollModal" style="background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">
            <i class="fas fa-poll"></i> Poll
        </button>
    </div>
</section>

<!-- Modal for Adding a Post -->
<form action="add_post.php" method="post" enctype="multipart/form-data" onsubmit="updateHiddenContent()">
    <div id="postModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <h2 class="text-center mb-4">Add a New News</h2>
                <input name="title" id="postTitle" type="text" placeholder="Enter post title" class="form-control mb-3" />
                <div class="mb-3">
                    <button type="button" onclick="formatText('bold')" class="btn btn-secondary"><b>B</b></button>
                    <button type="button" onclick="formatText('italic')" class="btn btn-secondary"><i>I</i></button>
                </div>
                <div contenteditable="true" id="postContent" class="form-control mb-3" style="min-height: 150px;" placeholder="Description..." ></div>
                <input type="hidden" name="content" id="hiddenContent" >
                <input type="file" name="image" id="postImage" class="form-control-file mb-3" accept="image/*" />
                <img id="imagePreview" class="image-preview" style="display: none" />
                <div class="text-right">
                    <button id="closeModalBtn" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="postBtn" class="btn btn-primary" type="submit" style="background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">Post</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal for Adding a Poll -->
<form action="add_poll.php" method="post">
    <div id="pollModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <h2 class="text-center mb-4">Create a Poll</h2>
                <input name="poll_question" type="text" placeholder="Enter poll question" class="form-control mb-3" />
                <div id="pollOptions" class="mb-3">
                    <input name="options[]" type="text" placeholder="Option 1" class="form-control mb-2" />
                </div>
                <button type="button" class="btn btn-success mb-3" onclick="addOption()" style="background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">+</button>
                <div class="text-right">
                    <button id="closePollModalBtn" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="createPollBtn" class="btn btn-primary" type="submit" style="background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">Create Poll</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- JavaScript for Dynamic Poll Options and Reset on Modal Close -->
<script>
    function addOption() {
        var pollOptions = document.getElementById('pollOptions');
        var newOptionIndex = pollOptions.children.length + 1;
        var newOption = document.createElement('input');
        newOption.type = 'text';
        newOption.name = 'options[]';
        newOption.placeholder = 'Option ' + newOptionIndex;
        newOption.className = 'form-control mb-2';
        pollOptions.appendChild(newOption);
    }

    // Reset poll options to one input field when the modal is hidden
    $('#pollModal').on('hidden.bs.modal', function () {
        var pollOptions = document.getElementById('pollOptions');
        pollOptions.innerHTML = '<input name="options[]" type="text" placeholder="Option 1" class="form-control mb-2" />';
    });
</script>
<script>
    $(document).ready(function () {
        // Trigger modal if success message exists
        <?php if (!empty($successMessage)) : ?>
            console.log('Triggering modal with message: <?php echo $successMessage; ?>'); // Debugging
            $('#successModal').modal('show');
        <?php endif; ?>
    });
</script>


<h3 class="mb-4" style="margin-left:50px;">The News</h3> 
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
                    echo '<div class="card shadow-sm" style="background-color: #fffaf6; border-radius: 35px;" >';
                    echo '<div class="card-body" style="background-color: #fffaf6; border-radius: 35px;">';

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
                    echo '<button type="submit" class="btn  btn-sm" style="background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">';
                    echo '<i class="fas fa-trash-alt"></i>';
                    echo '</button>';
                    echo '</form>';
                    echo '</div>';

                    // Edit button - Corrected 
                    echo '<button type="button" class="btn btn-sm" data-toggle="modal" data-target="#editModal' . $row['id'] . '" style="float:right; margin-bottom:20px; background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">'; 
                    echo '<i class="fas fa-pencil-alt"></i>'; 
                    echo '</button>';

                    // Post title and content
                    echo '<h5 class="card-title text-secondary title mt-3">' . $row['title'] . '</h5>';

                    // Validate image
                    if (!empty($row['image']) && file_exists(__DIR__ . '/' . $row['image'])) {
                        echo '<img src="' . $row['image'] . '" class="card-img-top" alt="' . '" style="width: 100%; height: auto; border-radius: 15px; margin-bottom: 15px;" />';
                    } else {
                        echo '<img src="Photos/placeholder.png" class="card-img-top" alt="Placeholder Image" style="width: 100%; height: auto; border-radius: 15px; margin-bottom: 15px;" />';
                    }

                    echo '<p class="card-text">' . nl2br(string: htmlspecialchars($row['content'])) . '</p>';
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
                    echo '<button type="submit" class="btn" style="background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">Save changes</button>';
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
        <!--News and Announcement-->
<style>
    #bottomNavbar .nav-link {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #6c757d; 
  text-decoration: none;
    }
</style>

<!-- Navigation Bar -->
<nav id="bottomNavbar" class="navbar fixed-bottom navbar-light bg-light">
<div id="nav-announcement" class="col-6">
            <a class="nav-link btn-nav" href="/l">
              <i class="fas fa-newspaper nav-link btn-nav"" style="font-size: 24px; color:black;"></i>
              <span style="color:black;">News</span>
            </a>
          </div>
          <div id="nav-announcement" class="col-6">
            <a class="nav-link btn-nav" href="announcement.php">
              <i class="fas fa-bullhorn" style="font-size: 24px"></i>
              <span>Announcement</span>
            </a>
          </div>
    </div>
     </div>
</nav>
<!-- JavaScript -->
 <script>
    document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.announcement-card');

    cards.forEach((card) => {
        card.addEventListener('click', function () {
            // Retrieve data from the clicked card
            const title = card.getAttribute('data-title');
            const content = card.getAttribute('data-content');
            const date = card.getAttribute('data-date');

            // Check if data exists
            if (title && content && date) {
                // Populate modal content
                document.getElementById('announcementTitle').textContent = title;
                document.getElementById('announcementContent').textContent = content;
                document.getElementById('announcementDate').textContent = date;
            } else {
                console.error("Missing data attributes for the announcement card.");
            }

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('viewAnnouncementModal'));
            modal.show();
        });
    });
});

 </script>


<!-- Bootstrap & JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="script.js">
</script>
</body>
</html>
