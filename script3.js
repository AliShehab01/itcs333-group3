
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

