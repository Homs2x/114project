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
        <a href="Login/Login.html" class="text-white"><button class="btn btn-primary mr-2" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;">Logout</button></a>
    </div>
    <div class="d-none d-md-block ml-3">
        <input class="form-control rounded-pill extended-search" placeholder="Search" type="text" />
    </div>
</header>

<!-- Add an Announcement -->
<section class="bg-white p-3 my-3 shadow-sm text-center">
    <div class="container">
        <button id="addPostBtn" class="btn btn-primary mr-2" data-toggle="modal" data-target="#postModal" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;">
            Create an Announcement
        </button>
    </div>
</section>

<!-- Modal for Creating an Announcement -->
<div id="postModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <h2 class="text-center mb-4">Create an Announcement</h2>
            <form action="add_announcement.php" method="post">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input name="title" id="postTitle" type="text" placeholder="Enter title" class="form-control mb-3" />
                </div>
                <div class="form-group">
                    <label for="content">Description/Content</label>
                    <textarea name="content" id="postContent" placeholder="Enter description/content" class="form-control mb-3" rows="4"></textarea>
                </div>
                <div class="text-right">
                    <button id="closeModalBtn" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="postBtn" class="btn btn-primary" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;" type="submit">Create Announcement</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal for Editing an Announcement -->
<div id="editModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <h2 class="text-center mb-4">Edit Announcement</h2>
            <form id="editAnnouncementForm" method="post" action="edit_announcement.php">
                <!-- Hidden field for ID -->
                <input type="hidden" name="id" id="editAnnouncementId" />

                <!-- Title Field -->
                <div class="form-group">
                    <label for="editTitle">Title</label>
                    <input name="title" id="editTitle" type="text" placeholder="Enter title" class="form-control mb-3" required />
                </div>

                <!-- Content Field -->
                <div class="form-group">
                    <label for="editContent">Description/Content</label>
                    <textarea name="content" id="editContent" placeholder="Enter description/content" class="form-control mb-3" rows="4" required></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="text-right">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="saveEditBtn" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;" type="submit">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal for Viewing Announcement -->
<div id="viewAnnouncementModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="announcementTitle" style="color:orange;"></h5>
            </div>
            <div class="modal-body" id="announcementContent">
                <p></p>
            </div>
            <div class="modal-footer">
                <!-- Close Button -->
                <button id="closePollModalBtn" class="btn btn-secondary" data-dismiss="modal" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;">Back to Announcements</button>
            </div>
        </div>
    </div>
</div>


<!-- What's New Section -->
<section class="bg-white p-3 my-3 shadow-sm">
    <div class="container">
        <h3 class="mb-4">Announcements</h3>
        <div class="row">
            <?php
            $conn = mysqli_connect("localhost", "root", "", "ccis_connect");
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $sql = "SELECT id_annou, title, content, created_at FROM tbl_announcements ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $formattedDate = date("F j, Y", strtotime($row['created_at']));
                    echo '<div class="card shadow-sm announcement-card" style="background-color: #ffe6bb; height:60px; margin-bottom:10px;" data-toggle="modal" data-target="#viewAnnouncementModal" data-title="' . htmlspecialchars($row['title']) . '" data-content="' . htmlspecialchars($row['content']) . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title" style="font-size:21px;position:absolute; bottom:40%;">' . htmlspecialchars($row['title']) . '</h5>'; 
                    echo '<div class=>';
                    echo '<i class="fas fa-trash-alt delete-icon" style="position:absolute;left:90%;cursor:pointer;" data-id="' . $row['id_annou'] . '"></i>';
                    echo '<i class="fas fa-pencil-alt edit-icon" style="position:absolute;left:84%;cursor:pointer;" 
                            data-id="' . $row['id_annou'] . '" 
                            data-title="' . htmlspecialchars($row['title']) . '" 
                            data-content="' . htmlspecialchars($row['content']) . '"></i>';
                            echo '</div>';

                    echo '<p class="text-muted" style="margin-top:18px;">Posted: ' . $formattedDate . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No new announcements.</p>';
            }
            mysqli_close($conn);
            ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editIcons = document.querySelectorAll('.edit-icon'); // Edit icons

    // Handle click on the edit icon
    editIcons.forEach(icon => {
        icon.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent triggering the parent card's click event

            // Get announcement data from the icon's data attributes
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const content = this.getAttribute('data-content');

            // Populate the modal fields with the announcement data
            document.getElementById('editAnnouncementId').value = id;
            document.getElementById('editTitle').value = title;
            document.getElementById('editContent').value = content;

            // Show the edit modal
            $('#editModal').modal('show');
        });
    });
});


    document.addEventListener('DOMContentLoaded', function () {
        const deleteIcons = document.querySelectorAll('.delete-icon');

        deleteIcons.forEach(icon => {
            icon.addEventListener('click', function (e) {
                e.stopPropagation(); // Prevent triggering parent click events
                const id = this.getAttribute('data-id');

                if (confirm('Are you sure you want to delete this announcement?')) {
                    fetch('delete_announcement.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'id=' + encodeURIComponent(id),
                    })
                        .then(response => response.text())
                        .then(data => {
                            alert(data); // Show success/error message
                            if (data.includes('successfully')) {
                                this.closest('.card').remove(); // Remove the card on success
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the announcement.');
                        });
                }
            });
        });
    });
</script>


<!-- JavaScript to Populate Modal -->
<script>
document.querySelectorAll('.announcement-card').forEach(card => {
    card.addEventListener('click', function () {
        const title = this.getAttribute('data-title');
        const content = this.getAttribute('data-content');

        // Populate the view modal
        const modal = document.getElementById('viewAnnouncementModal');
        modal.querySelector('.modal-title').textContent = title;
        modal.querySelector('.modal-body').innerHTML = `<p>${content}</p>`;

        // Show the modal
        $('#viewAnnouncementModal').modal('show');
    });
});


    $(document).ready(function() {
        $('#viewAnnouncementModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var idAnnou = button.data('id_annou'); // Extract `id_annou` from data-* attributes
            var title = button.data('title'); // Extract `title` from data-* attributes
            var content = button.data('content'); // Extract `content` from data-* attributes
            var modal = $(this);
            modal.find('.modal-title').text(title); // Update the modal title
            modal.find('.modal-body').html('<p>' + content + '</p>'); // Update the modal content
        });
    });

    $(document).ready(function () {
    // Handle the modal show event to populate content
    $('#viewAnnouncementModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var title = button.data('title');
        var content = button.data('content');
        var modal = $(this);

        modal.find('.modal-title').text(title);
        modal.find('.modal-body').html(content); // Use `.html()` for formatted content
    });

    // Close the modal and scroll back to the announcements section
    $('#closeModalButton').on('click', function () {
        $('#viewAnnouncementModal').modal('hide'); // Close the modal
        $('html, body').animate({
            scrollTop: $('.container').offset().top, // Adjust '.container' to your announcements section
        }, 500); // Smooth scroll duration in milliseconds
    });
});

</script>


<!-- News and Announcement -->
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
              <i class="fas fa-newspaper nav-link btn-nav"" style="font-size: 24px; "></i>
              <span>News</span>
            </a>
            
          </div>
          <div id="nav-announcement" class="col-6">
            <a class="nav-link btn-nav" href="poll.php">
            <i class="fas fa-poll"  style="font-size: 24px" ></i>
              <span>View Polls</span>
            </a>
          </div>
          
          <div id="nav-announcement" class="col-12">
            <a class="nav-link btn-nav" href="">
              <i class="fas fa-bullhorn" style="color:black; font-size: 24px"></i>
              <span style="color:black;">Announcement</span>
            </a>
          </div>
          
          
    </div>
    
</nav>

<!-- JavaScript -->
<!-- Bootstrap & JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script>
    $(document).ready(function() {
        $('#viewAnnouncementModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var title = button.data('title');
            var content = button.data('content');
            var modal = $(this);
            modal.find('.modal-title').text(title);
            modal.find('.modal-body').text(content);
        });
    });
</script>
</body>
</html>
