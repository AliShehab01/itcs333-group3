let reviews = [];
let filteredReviews = [];
let currentPage = 1;
const pageSize = 6;

async function fetchReviews(page = 1) {
  const reviewsContainer = document.querySelector(".reviews-list");
  reviewsContainer.innerHTML = '<div class="text-center w-100">Loading reviews...</div>';
  const offset = (page - 1) * pageSize;

  try {
    const response = await fetch(`api/getReview.php?limit=${pageSize}&offset=${offset}`);
    if (!response.ok) throw new Error("Network response was not ok");

    reviews = await response.json();
    filteredReviews = [...reviews];
    displayReviews();
    renderPagination(page);
    currentPage = page;
  } catch (error) {
    console.error("Fetching reviews failed:", error);
    reviewsContainer.innerHTML = '<div class="text-danger w-100">Failed to load reviews. Please try again later.</div>';
  }
}

function displayReviews() {
  const reviewsContainer = document.querySelector(".reviews-list");
  reviewsContainer.innerHTML = '';

  if (!filteredReviews.length) {
    reviewsContainer.innerHTML = '<div class="text-center w-100">No reviews found.</div>';
    return;
  }

  filteredReviews.forEach(review => {
    const reviewCard = document.createElement("div");
    reviewCard.className = "col-md-4 mb-4";
    reviewCard.innerHTML = `
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">${review.courseName}</h5>
          <h6 class="card-subtitle mb-2 text-muted">Professor: ${review.professorName}</h6>
          <p class="card-text">${review.reviewText}</p>
          <span class="badge badge-primary mb-2">Rating: ${review.rating}</span>
          <span class="badge badge-secondary mb-2">${review.semester}</span>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Submitted: ${review.created_at || new Date().toLocaleDateString()}</small>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${review.id}">Delete</button>
          </div>
        </div>
      </div>
    `;
    reviewsContainer.appendChild(reviewCard);
  });

  document.querySelectorAll(".delete-btn").forEach(button => {
    button.addEventListener("click", async () => {
      const id = button.getAttribute("data-id");
      if (confirm("Are you sure you want to delete this review?")) {
        try {
          const response = await fetch("api/deleteReview.php", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id })
          });
          const result = await response.json();
          if (response.ok) {
            reviews = reviews.filter(r => r.id != id);
            filteredReviews = filteredReviews.filter(r => r.id != id);
            displayReviews();
          } else {
            alert("Failed to delete: " + result.error);
          }
        } catch (error) {
          console.error("Delete error:", error);
          alert("Error deleting review.");
        }
      }
    });
  });
}

function handleSearchAndSort() {
  const query = document.getElementById("searchInput").value.toLowerCase();
  const sort = document.getElementById("sortSelect").value;

  filteredReviews = reviews.filter(review =>
    review.courseName.toLowerCase().includes(query) ||
    review.professorName.toLowerCase().includes(query)
  );

  if (sort === "high") {
    filteredReviews.sort((a, b) => b.rating - a.rating);
  } else if (sort === "low") {
    filteredReviews.sort((a, b) => a.rating - b.rating);
  }

  displayReviews();
}

function renderPagination(current) {
  const paginationContainer = document.querySelector(".pagination");
  paginationContainer.innerHTML = '';

  const prev = document.createElement("li");
  prev.className = `page-item ${current === 1 ? 'disabled' : ''}`;
  prev.innerHTML = `<a class="page-link" href="#">Previous</a>`;
  prev.onclick = () => fetchReviews(current - 1);
  paginationContainer.appendChild(prev);

  for (let i = 1; i <= 5; i++) {
    const page = document.createElement("li");
    page.className = `page-item ${current === i ? 'active' : ''}`;
    page.innerHTML = `<a class="page-link" href="#">${i}</a>`;
    page.onclick = () => fetchReviews(i);
    paginationContainer.appendChild(page);
  }

  const next = document.createElement("li");
  next.className = `page-item`;
  next.innerHTML = `<a class="page-link" href="#">Next</a>`;
  next.onclick = () => fetchReviews(current + 1);
  paginationContainer.appendChild(next);
}

async function handleFormSubmit(e) {
  e.preventDefault();

  const newReview = {
    courseName: document.getElementById("courseName").value,
    professorName: document.getElementById("professorName").value,
    rating: document.getElementById("rating").value,
    semester: document.getElementById("semester").value,
    reviewText: document.getElementById("reviewText").value
  };

  try {
    const response = await fetch("api/postReview.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(newReview)
    });

    if (!response.ok) throw new Error("Failed to add review");

    const savedReview = await response.json();
    reviews.unshift(savedReview);
    filteredReviews = [...reviews];
    displayReviews();
    document.getElementById("reviewForm").reset();
    alert("Thank you for submitting your review!");
  } catch (error) {
    console.error("Error:", error);
    alert("Failed to submit your review. Please try again later.");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  fetchReviews();
  document.getElementById("reviewForm").addEventListener("submit", handleFormSubmit);
  document.getElementById("searchInput").addEventListener("input", handleSearchAndSort);
  document.getElementById("sortSelect").addEventListener("change", handleSearchAndSort);
});
