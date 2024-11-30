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
        <a href="Login/Login.html" class="text-white"><button class="btn btn-primary mr-2" style="background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">Logout</button></a>
    </div>
    <div class="d-none d-md-block ml-3">
        <input class="form-control rounded-pill extended-search" placeholder="Search" type="text" />
    </div>
</header>

<section class="bg-white p-3 my-3 shadow-sm text-center">
    <button id="addPollBtn" class="btn btn-primary" data-toggle="modal" data-target="#pollModal" style="background-color: #fd7e14;  border-color: #fd7e14;  color: #fff;">
        <i class="fas fa-poll"></i> Poll
    </button>

    

</section>

<!-- Modal for Adding a Poll -->
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
                    <button id="closePollModalBtn" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="createPollBtn" class="btn btn-primary" type="submit" style="background-color: #fd7e14; border-color: #fd7e14; color: #fff;">Create Poll</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    let questionIndex = 0;

    // Add a new question
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

    // Add a new sub-option (answer) to a specific question
    function addSubOption(button) {
        const suboptionsContainer = button.previousElementSibling; // Find the suboptions container
        const input = document.createElement('input');
        input.type = 'text';
        input.name = `suboptions[${questionIndex}][]`;
        input.placeholder = 'Enter an answer option';
        input.className = 'form-control mb-2';

        suboptionsContainer.appendChild(input);
    }
</script>

<!-- Include Bootstrap JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

<nav id="bottomNavbar" class="navbar fixed-bottom navbar-light bg-light">
    <div id="nav-announcement" class="col-6">
        <a class="nav-link btn-nav" href="/l">
            <i class="fas fa-newspaper nav-link btn-nav" style="font-size: 24px;"></i>
            <span>News</span>
        </a>
    </div>
    <div id="nav-announcement" class="col-6">
        <a class="nav-link btn-nav" href="poll.php">
            <i class="fas fa-poll" style="font-size: 24px; color:black;"></i>
            <span style="color:black;">View Polls</span>
        </a>
    </div>
    <div id="nav-announcement" class="col-12">
        <a class="nav-link btn-nav" href="announcement.php">
            <i class="fas fa-bullhorn" style="font-size: 24px;"></i>
            <span>Announcement</span>
        </a>
    </div>
</nav>

</body>
</html>
