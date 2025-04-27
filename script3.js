
let reviews = [];
let currentPage = 1;
const itemsPerPage = 3;

// Fetch reviews from a mock API
async function fetchReviews() {
  const reviewsContainer = document.querySelector(".row");
  reviewsContainer.innerHTML = '<div class="text-center w-100">Loading reviews...</div>';

  try {
    const response = await fetch("https://jsonplaceholder.typicode.com/posts?_limit=15");
    if (!response.ok) throw new Error("Network response was not ok");

    const data = await response.json();

    // Dummy transform: adapt JSONPlaceholder data to review format
    reviews = data.map(post => ({
      courseName: `Course ${post.id}`,
      professorName: `Prof. ${post.userId}`,
      rating: Math.floor(Math.random() * 5) + 1,
      semester: "Fall 2025",
      reviewText: post.body,
      date: new Date().toLocaleDateString()
    }));

    displayReviews();
  } catch (error) {
    reviewsContainer.innerHTML = '<div class="text-danger w-100">Failed to load reviews. Please try again later.</div>';
    console.error("Fetching reviews failed:", error);
  }
}

// Display reviews with pagination
function displayReviews(page = 1) {
  const reviewsContainer = document.querySelector(".row");
  reviewsContainer.innerHTML = "";

  const start = (page - 1) * itemsPerPage;
  const paginatedReviews = reviews.slice(start, start + itemsPerPage);

  if (paginatedReviews.length === 0) {
    reviewsContainer.innerHTML = '<div class="text-center w-100">No reviews found.</div>';
    return;
  }

  paginatedReviews.forEach(review => {
    const card = document.createElement("div");
    card.className = "col-md-4 mb-4";
    card.innerHTML = `
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">${review.courseName}</h5>
          <h6 class="card-subtitle mb-2 text-muted">Professor: ${review.professorName}</h6>
          <p class="card-text">${review.reviewText}</p>
          <span class="badge badge-primary mb-2">Rating: ${review.rating}</span>
          <span class="badge badge-secondary mb-2">${review.semester}</span>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Submitted: ${review.date}</small>
          </div>
        </div>
      </div>
    `;
    reviewsContainer.appendChild(card);
  });

  updatePaginationControls();
}

// Search functionality
function setupSearch() {
  const searchInput = document.createElement("input");
  searchInput.className = "form-control mb-4";
  searchInput.placeholder = "Search by course or professor...";

  const container = document.querySelector(".container.mt-5");
  container.insertBefore(searchInput, container.children[1]);

  searchInput.addEventListener("input", function () {
    const query = this.value.toLowerCase();
    const filtered = reviews.filter(r =>
      r.courseName.toLowerCase().includes(query) ||
      r.professorName.toLowerCase().includes(query)
    );
    displayFilteredReviews(filtered);
  });
}

function displayFilteredReviews(filteredReviews) {
  const reviewsContainer = document.querySelector(".row");
  reviewsContainer.innerHTML = "";

  if (filteredReviews.length === 0) {
    reviewsContainer.innerHTML = '<div class="text-center w-100">No matching reviews found.</div>';
    return;
  }

  filteredReviews.forEach(review => {
    const card = document.createElement("div");
    card.className = "col-md-4 mb-4";
    card.innerHTML = `
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">${review.courseName}</h5>
          <h6 class="card-subtitle mb-2 text-muted">Professor: ${review.professorName}</h6>
          <p class="card-text">${review.reviewText}</p>
          <span class="badge badge-primary mb-2">Rating: ${review.rating}</span>
          <span class="badge badge-secondary mb-2">${review.semester}</span>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Submitted: ${review.date}</small>
          </div>
        </div>
      </div>
    `;
    reviewsContainer.appendChild(card);
  });
}

// Sorting functionality
function setupSort() {
  const sortSelect = document.createElement("select");
  sortSelect.className = "form-control mb-4";
  sortSelect.innerHTML = `
    <option value="">Sort by...</option>
    <option value="high">Highest Rating</option>
    <option value="low">Lowest Rating</option>
  `;

  const container = document.querySelector(".container.mt-5");
  container.insertBefore(sortSelect, container.children[2]);

  sortSelect.addEventListener("change", function () {
    if (this.value === "high") {
      reviews.sort((a, b) => b.rating - a.rating);
    } else if (this.value === "low") {
      reviews.sort((a, b) => a.rating - b.rating);
    }
    displayReviews(1);
  });
}

// Pagination controls
function updatePaginationControls() {
  const pagination = document.querySelector(".pagination");
  const totalPages = Math.ceil(reviews.length / itemsPerPage);

  pagination.innerHTML = `
    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a>
    </li>
    ${Array.from({ length: totalPages }, (_, i) => `
      <li class="page-item ${i + 1 === currentPage ? 'active' : ''}">
        <a class="page-link" href="#" onclick="changePage(${i + 1})">${i + 1}</a>
      </li>
    `).join('')}
    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a>
    </li>
  `;
}

function changePage(page) {
  if (page < 1 || page > Math.ceil(reviews.length / itemsPerPage)) return;
  currentPage = page;
  displayReviews(page);
}

// Form validation for submission
function setupFormValidation() {
  const form = document.getElementById("reviewForm");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const courseName = document.getElementById("courseName").value.trim();
    const professorName = document.getElementById("professorName").value.trim();
    const rating = document.getElementById("rating").value;
    const semester = document.getElementById("semester").value.trim();
    const reviewText = document.getElementById("reviewText").value.trim();

    if (!courseName || !professorName || !rating || !semester || !reviewText) {
      alert("Please fill in all fields correctly.");
      return;
    }

    const newReview = {
      courseName,
      professorName,
      rating: Number(rating),
      semester,
      reviewText,
      date: new Date().toLocaleDateString()
    };

    reviews.unshift(newReview); // Add to the top
    form.reset();
    alert("Review submitted successfully!");
    displayReviews(1);
  });
}

// Initialize everything
fetchReviews();
setupSearch();
setupSort();
setupFormValidation();
