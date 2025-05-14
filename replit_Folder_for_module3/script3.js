let reviews = [];

async function fetchReviews() {
  const reviewsContainer = document.querySelector(".row");
  reviewsContainer.innerHTML = '<div class="text-center w-100">Loading reviews...</div>';

  try {
    const response = await fetch("/api/getReview.php");
    if (!response.ok) throw new Error("Network response was not ok");

    reviews = await response.json();
    displayReviews();
  } catch (error) {
    reviewsContainer.innerHTML = '<div class="text-danger w-100">Failed to load reviews. Please try again later.</div>';
    console.error("Fetching reviews failed:", error);
    fallbackToMockData();
  }
}

async function fallbackToMockData() {
  try {
    const response = await fetch("https://jsonplaceholder.typicode.com/posts?_limit=15");
    if (!response.ok) throw new Error("Network response was not ok");

    const data = await response.json();

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
    console.error("Fallback to mock data also failed:", error);
  }
}

function displayReviews() {
  const reviewsContainer = document.querySelector(".row");
  
  if (!reviews.length) {
    reviewsContainer.innerHTML = '<div class="text-center w-100">No reviews available.</div>';
    return;
  }
  
  reviewsContainer.innerHTML = '';
  
  reviews.forEach(review => {
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
            <small class="text-muted">Submitted: ${review.created_at || review.date}</small>
          </div>
        </div>
      </div>
    `;
    reviewsContainer.appendChild(reviewCard);
  });
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
    const response = await fetch('/api/postReview', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(newReview)
    });
    
    if (!response.ok) {
      throw new Error('Failed to add review');
    }
    
    const savedReview = await response.json();
    reviews.unshift(savedReview);
    displayReviews();
    document.getElementById("reviewForm").reset();
    alert("Thank you for submitting your review!");
  } catch (error) {
    console.error('Error:', error);
    alert('Failed to submit your review. Please try again later.');
    newReview.id = reviews.length + 1;
    newReview.created_at = new Date().toLocaleDateString();
    reviews.unshift(newReview);
    displayReviews();
    document.getElementById("reviewForm").reset();
  }
}

document.addEventListener("DOMContentLoaded", () => {
  fetchReviews();
  document.getElementById("reviewForm").addEventListener("submit", handleFormSubmit);
});
