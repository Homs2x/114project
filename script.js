// Global variable to store the post being edited
let currentEditingPost = null;

// Function to trigger the Edit Modal
function editPost(editIcon) {
  const post = editIcon.closest(".post-section");
  currentEditingPost = post;

  // Populate the modal fields with current post data
  const title = post.querySelector(".post-title").textContent;
  const content = post.querySelector(".cardtext .preview-text").textContent;

  document.getElementById("EditpostTitle").value = title;
  document.getElementById("EditpostContent").value = content;

  // Open the Edit Post Modal
  $("#editPostModal").modal("show");
}

// Save the Edited Post
document.getElementById("saveEditBtn").addEventListener("click", function () {
  if (currentEditingPost) {
    const editedTitle = document.getElementById("EditpostTitle").value;
    const editedContent = document.getElementById("EditpostContent").value;

    if (editedTitle && editedContent) {
      currentEditingPost.querySelector(".post-title").textContent = editedTitle;
      currentEditingPost.querySelector(".cardtext .preview-text").textContent =
        editedContent;

      currentEditingPost = null;
      $("#editPostModal").modal("hide");
    }
  }
});

// Preview selected image
document
  .getElementById("postImage")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function (e) {
      const preview = document.getElementById("imagePreview");
      preview.src = e.target.result;
      preview.style.display = "block";
    };
    if (file) reader.readAsDataURL(file);
  });

// Post button functionality
document.getElementById("postBtn").addEventListener("click", function () {
  const title = document.getElementById("postTitle").value;
  const content = document.getElementById("postContent").value;
  const imagePreview = document.getElementById("imagePreview");
  const imageSrc =
    imagePreview.style.display !== "none" ? imagePreview.src : null;
  const now = new Date();
  const formattedDate = `${now.toLocaleDateString()} ${now.toLocaleTimeString()}`;

  if (title && content) {
    const postContainer = document.getElementById("postContainer");
    const postDiv = document.createElement("div");
    postDiv.className = "post-section";

    postDiv.innerHTML = `
        <h4 class="post-title">${title}</h4>
        <p class="post-time">${formattedDate}</p>
        <p class="post-content">${content}</p>
        ${
          imageSrc
            ? `<img src="${imageSrc}" alt="Post image" class="img-fluid rounded mb-3">`
            : ""
        }
        <span class="like-icon text-danger fas fa-heart"></span>
    `;

    postContainer.prepend(postDiv);

    document.getElementById("successMessage").style.display = "block";
    setTimeout(() => {
      document.getElementById("successMessage").style.display = "none";
    }, 2000);

    document.getElementById("postTitle").value = "";
    document.getElementById("postContent").value = "";
    imagePreview.style.display = "none";
    imagePreview.src = "";
  }
});
//New

document.addEventListener("DOMContentLoaded", function () {
  fetchPosts();
});
function fetchPosts() {
  fetch("fetch_posts.php")
    .then((response) => response.json())
    .then((data) => {
      const postContainer = document.getElementById("postContainer");
      postContainer.innerHTML = "";
      data.forEach((post) => {
        const postElement = document.createElement("div");
        postElement.className = "post mb-4";
        postElement.innerHTML = ` <h3>${post.title}</h3> <p>${
          post.content
        }</p> ${
          post.image
            ? `<img src="${post.image}" alt="${post.title}" class="img-fluid mb-3" />`
            : ""
        } <small class="text-muted">${new Date(
          post.created_at
        ).toLocaleString()}</small> `;
        postContainer.appendChild(postElement);
      });
    })
    .catch((error) => console.error("Error fetching posts:", error));
}
function formatText(command) {
  document.execCommand(command, false, null);
  updateHiddenContent();
}

document
  .getElementById("postContent")
  .addEventListener("input", updateHiddenContent);

function updateHiddenContent() {
  var content = document.getElementById("postContent").innerHTML;
  document.getElementById("hiddenContent").value = content;
}

document.getElementById("postContent").addEventListener("focus", function () {
  document.execCommand("defaultParagraphSeparator", false, "p");
});
function formatText(command) {
  document.execCommand(command, false, null);
  updateHiddenContent();
}

function updateHiddenContent() {
  var content = document.getElementById("postContent").innerHTML;
  document.getElementById("hiddenContent").value = content;
}

document.getElementById("postContent").addEventListener("focus", function () {
  document.execCommand("defaultParagraphSeparator", false, "p");
});
function formatText(command) {
  document.execCommand(command, false, null);
  updateHiddenContent();
}

function updateHiddenContent() {
  var content = document.getElementById("postContent").innerHTML;
  document.getElementById("hiddenContent").value = content;
}

document.getElementById("postContent").addEventListener("focus", function () {
  document.execCommand("defaultParagraphSeparator", false, "p");
});
document.addEventListener("DOMContentLoaded", function () {
  const cards = document.querySelectorAll(".announcement-card");

  cards.forEach((card) => {
    card.addEventListener("click", function () {
      // Logic to populate modal (optional)
      const modal = new bootstrap.Modal(
        document.getElementById("viewAnnouncementModal")
      );
      modal.show();
    });
  });
});
// script.js
document.addEventListener("DOMContentLoaded", (event) => {
  // Existing modal code...

  // Handle delete button click
  document.querySelectorAll(".delete-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const announcementId = this.getAttribute("data-id");
      if (confirm("Are you sure you want to delete this announcement?")) {
        fetch("delete_announcement.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: "id=" + encodeURIComponent(announcementId),
        })
          .then((response) => response.text())
          .then((data) => {
            alert(data);
            location.reload();
          })
          .catch((error) => console.error("Error:", error));
      }
    });
  });
});
