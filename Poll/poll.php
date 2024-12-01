<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="../Photos/CCISLOGO.png" />
    <title>CCIS Connect</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css" />
</head>
<body class="bg-light">

<!-- Header -->
<header class="text-white p-3 d-flex align-items-center justify-content-between" style="background-color: #ed5500">
    <div class="d-flex align-items-center">
        <img src="../Photos/CCISLOGO.png" alt="CCIS Logo" height="40" width="40" class="mr-2" />
        <div>
            <h1 class="h5 mb-0">CCIS CONNECT</h1>
            <small>College of Computing and Information Sciences</small>
        </div>
    </div>
    
    <a href="../Login/Login.html" class="text-white"><button class="btn btn-primary mr-2" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;">Logout</button></a>
</header>

<!-- Poll Section -->
<section class="bg-white p-3 my-3 shadow-sm text-center">
    <!-- Button to Open the Poll Modal -->
    <button id="addPollBtn" class="btn btn-primary" type="button" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;">
        <i class="fas fa-poll"></i> Poll
    </button>
</section>

<!-- Placeholder for displaying existing polls -->
<section class="bg-white p-3 my-3 shadow-sm">
    <div class="container">
        <h3 class="mb-4">Polls</h3>
        <div class="row">
            <?php
            $conn = mysqli_connect("localhost", "root", "", "ccis_connect");
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $sql = "SELECT id, title, created_at FROM tbl_polls ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $formattedDate = date("F j, Y", strtotime($row['created_at']));
                    echo '<div class="card shadow-sm announcement-card" style="background-color: #ffe6bb; height:60px; margin-bottom:10px;" data-toggle="modal" data-target="#viewAnnouncementModal" data-title="' . htmlspecialchars($row['title']) . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title" style="font-size:21px;position:absolute; bottom:40%;">' . htmlspecialchars($row['title']) . '</h5>'; 
                    echo '<div class=>';
                    echo '<i class="fas fa-trash-alt delete-icon" style="position:absolute;left:90%;cursor:pointer;" data-id="' . $row['id'] . '"></i>';
                    echo '<i class="fas fa-pencil-alt edit-icon" style="position:absolute;left:84%;cursor:pointer;" 
                            data-id="' . $row['id'] . '" 
                            data-title="' . htmlspecialchars($row['title']) . '"></i>';
                            echo '</div>';

                    echo '<p class="text-muted" style="margin-top:18px;">Posted: ' . $formattedDate . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No polls.</p>';
            }
            mysqli_close($conn);
            ?>
        </div>
    </div>
</section>

<!-- Create Poll Modal -->
<form action="add_poll.php" method="post">
    <div id="pollModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <h2 class="text-center mb-4">Create a Poll</h2>
                
                <!-- Poll Title -->
                <h3 class=" mb-4">Title</h3>
                <input name="poll_question" type="text" placeholder="Enter poll title" class="form-control mb-3" required />

                <!-- Container for Poll Questions and Sub-options -->
                <h4 class=" mb-4">Question</h4>
                <div id="pollQuestions" class="mb-3">
                    <!-- Example Question Block -->
                    <div class="question-block mb-3">
                        <input name="questions[]" type="text" placeholder="Enter a question" class="form-control mb-2" required />
                        <div class="suboptions">
                            <input name="suboptions[0][]" type="text" placeholder="Enter an answer option" class="form-control mb-2" />
                        </div>
                        <button type="button" class="btn btn-success btn-sm mb-2" onclick="addSubOption(this)" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;">+ Add Answer Option</button>
                    </div>
                </div>

                <!-- Add Question Button -->
                <button type="button" class="btn btn-success mb-3" onclick="addQuestion()" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;">+ Add Question</button>

                <!-- Modal Actions -->
                <div class="text-right">
                    <button id="closePollModalBtn" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="createPollBtn" class="btn btn-primary" type="submit" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;">Create Poll</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Edit Poll Modal -->
<div id="editPollModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-4">
            <h2 class="text-center mb-4">Edit Poll</h2>
            <form action="update_poll.php" method="post" id="editPollForm">
                <!-- Poll ID (Hidden Input) -->
                <input type="hidden" name="id" id="editPollId" />

                <!-- Poll Title -->
                <div class="form-group mb-4">
                    <label for="editPollTitle" class="form-label">Poll Title</label>
                    <input type="text" name="title" id="editPollTitle" class="form-control" required />
                </div>

                <!-- Questions and Suboptions -->
                <div id="editPollQuestionsContainer" class="mb-4">
                    <!-- Dynamic content will be populated here via JavaScript -->
                </div>

                <!-- Add New Question Button -->
                <button type="button" class="btn btn-success mb-3" id="addQuestionBtn" onclick="addEditQuestion()">
                    + Add Question
                </button>

                <!-- Modal Actions -->
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteIcons = document.querySelectorAll('.delete-icon');

        deleteIcons.forEach(icon => {
            icon.addEventListener('click', function (e) {
                e.stopPropagation(); // Prevent triggering parent click events
                const id = this.getAttribute('data-id');

                if (confirm('Are you sure you want to delete this poll?')) {
                    fetch('delete_poll.php', {
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

    let questionIndex = 0;

    // Add a new question to the poll
    function addQuestion() {
        questionIndex++; // Increment question index
        const pollQuestions = document.getElementById('pollQuestions');

        const questionBlock = document.createElement('div');
        questionBlock.className = 'question-block mb-3';

        questionBlock.innerHTML = `
            <input name="questions[]" type="text" placeholder="Enter a question" class="form-control mb-2" required />
            <div class="suboptions">
                <input name="suboptions[${questionIndex}][]" type="text" placeholder="Enter an answer option" class="form-control mb-2" />
            </div>
            <button type="button" class="btn btn-success btn-sm mb-2" onclick="addSubOption(this)" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;">+ Add Answer Option</button>
        `;

        pollQuestions.appendChild(questionBlock);
    }

    // Add a new answer option to a specific question
    function addSubOption(button) {
        const suboptionsContainer = button.previousElementSibling; // Find the suboptions container
        const input = document.createElement('input');
        input.type = 'text';
        input.name = `suboptions[${questionIndex}][]`;
        input.placeholder = 'Enter an answer option';
        input.className = 'form-control mb-2';

        suboptionsContainer.appendChild(input);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Fetch and display poll data
        fetch('fetch_poll.php')
            .then(response => response.json())
            .then(data => {
                const pollContent = document.getElementById('pollContent');
                let content = '';
                data.forEach(poll => {
                    const postedDate = new Date(poll.created_at).toLocaleDateString();
                    content += `
                        <div class="card mb-3" data-id="${poll.id}">
                            <div class="card-body" style="background-color:orange;">
                                <h5 class="card-title">${poll.title}</h5>
                                <p class="card-text"><small class="text-muted">Posted: ${postedDate}</small></p>
                                <i class="fas fa-trash-alt delete-icon" style="position:absolute; right:5%;  bottom:40%; cursor:pointer;"></i>
                                <i class="fas fa-pencil-alt edit-icon" style="position:absolute; right:10%; bottom:40%;"></i>
                            </div>
                        </div>
                    `;
                });
                pollContent.innerHTML = content;

                // Add event listeners for delete icons
                document.querySelectorAll('.delete-icon').forEach(icon => {
                    icon.addEventListener('click', function () {
                        const pollId = this.closest('.card').getAttribute('data-id');
                        if (confirm('Are you sure you want to delete this poll?')) {
                            fetch('delete_poll.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `id=${pollId}`
                            })
                            .then(response => response.text())
                            .then(result => {
                                alert(result);
                                this.closest('.card').remove(); // Remove the poll card from the DOM
                            })
                            .catch(error => console.error('Error:', error));
                        }
                    });
                });
            });
    });

    // Show the modal when the "Poll" button is clicked
    document.getElementById('addPollBtn').addEventListener('click', function () {
        const myModal = new bootstrap.Modal(document.getElementById('pollModal'));
        myModal.show();
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Fetch and display poll data
        fetch('fetch_poll.php')
            .then(response => response.json())
            .then(data => {
                const pollContent = document.getElementById('pollContent');
                let content = '';
                data.forEach(poll => {
                    const postedDate = new Date(poll.created_at).toLocaleDateString();
                    content += `
                        <div class="card mb-3" data-id="${poll.id}">
                            <div class="card-body" style="background-color:orange;">
                                <h5 class="card-title">${poll.title}</h5>
                                <p class="card-text"><small class="text-muted">Posted: ${postedDate}</small></p>
                                <i class="fas fa-trash-alt delete-icon" style="position:absolute; right:5%; bottom:40%; cursor:pointer;"></i>
                                <i class="fas fa-pencil-alt edit-icon" style="position:absolute; right:10%; bottom:40%; cursor: pointer;"></i>

                            </div>
                        </div>
                    `;
                });
                pollContent.innerHTML = content;

                // Add event listeners for edit icons
document.querySelectorAll('.edit-icon').forEach(icon => {
    icon.addEventListener('click', function () {
        const pollId = this.closest('.card').getAttribute('data-id');
        fetch(`get_poll.php?id=${pollId}`)
            .then(response => response.json())
            .then(poll => {
                prefillEditPollModal(poll);
            })
            .catch(error => console.error('Error fetching poll:', error));
    });
});

            });
    });

    // Prefill the modal with the poll data
    function prefillEditPollModal(poll) {
        document.getElementById('editPollId').value = poll.id;
        document.getElementById('editPollTitle').value = poll.title;

        // Clear existing questions
        const container = document.getElementById('editPollQuestionsContainer');
        container.innerHTML = '';

        // Add questions and their suboptions dynamically
        poll.questions.forEach((question, index) => {
            addEditQuestion(question.text, question.suboptions.map(sub => sub.text));
        });

        // Show the modal
        const editPollModal = new bootstrap.Modal(document.getElementById('editPollModal'));
        editPollModal.show();
    }

</script>

<!-- Include Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    body {
        padding-bottom: 60px; /* Space for the fixed navbar */
    }
    #bottomNavbar {
        height: 60px; /* Adjust height as needed */
    }
    #bottomNavbar .nav-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        text-decoration: none;
        padding: 0;
    }
    #bottomNavbar .col-2 {
        padding: 0; 
    }
</style>

<nav id="bottomNavbar" class="navbar fixed-bottom navbar-light bg-light">
    <div id="nav-announcement" class="col-2">
        <a class="nav-link btn-nav" href="../index.php">
            <i class="fas fa-newspaper nav-link btn-nav" style="font-size: 24px;"></i>
            <span>News</span>
        </a>
    </div>
    <div id="nav-announcement" class="col-2">
        <a class="nav-link btn-nav" href="">
            <i class="fas fa-poll nav-link btn-nav" style="font-size: 24px;"></i>
            <span>Poll</span>
        </a>
    </div>
    <div id="nav-announcement" class="col-2">
        <a class="nav-link btn-nav" href="../Announcement/announcement.php">
            <i class="fas fa-bullhorn nav-link btn-nav" style="font-size: 24px;"></i>
            <span>Announcement</span>
        </a>
    </div>
</nav>

</body>
</html>
