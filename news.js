news.js:

function loadNews() {
const search = document.getElementById("searchInput")?.value || '';
const category = document.getElementById("categoryFilter")?.value || '';
const newsContainer = document.getElementById("newsContainer");

if (!newsContainer) return;

fetch(`/replit_for_Module8/api/get_news.php?search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}`)
.then(response => response.json())
.then(data => {
newsContainer.innerHTML = "";

if (data.length === 0) {
newsContainer.innerHTML = '<div class="col-12 text-center"><h3>No news found</h3></div>';
return;
}

data.forEach(news => {
const col = document.createElement("div");
col.className = "col-md-4 mb-4";
col.innerHTML = `
<div class="card h-100 shadow">
<img src="${news.image_url || '/replit_for_Module8/s40image.jpg'}" class="card-img-top" alt="News Image" style="height: 200px; object-fit: cover;">
<div class="card-body">
<h5 class="card-title">${news.title}</h5>
<p class="card-text">${news.summary || news.content}</p>
<a href="/replit_for_Module8/news_details.html?id=${news.id}" class="btn btn-primary mt-auto">Read More</a>
</div>
</div>
`;
newsContainer.appendChild(col);
});
})
.catch(err => {
console.error("Error loading news:", err);
newsContainer.innerHTML = '<div class="col-12 text-center"><h3>Error loading news</h3></div>';
});
}

document.addEventListener("DOMContentLoaded", () => {
loadNews();

const searchButton = document.getElementById("searchButton");
const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");

if (searchButton) {
searchButton.addEventListener("click", (e) => {
e.preventDefault();
loadNews();
});
}

if (searchInput) {
searchInput.addEventListener("keypress", (e) => {
if (e.key === "Enter") {
e.preventDefault();
loadNews();
}
});
}

if (categoryFilter) {
categoryFilter.addEventListener("change", () => loadNews());
}
});
