function loadNews() {
    const search = document.getElementById("searchInput")?.value || '';
    const category = document.getElementById("categoryFilter")?.value || '';

    fetch(`/replit_for_Module8/api/get_news.php?search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}`)
        .then(response => response.json())
        .then(data => {
            const newsContainer = document.getElementById("newsContainer");
            if (!newsContainer) return;

            newsContainer.innerHTML = "";
            data.forEach(news => {
                const col = document.createElement("div");
                col.className = "col-md-4 mb-4";
                col.innerHTML = `
                    <div class="card h-100">
                        <img src="${news.image_url}" class="card-img-top" alt="News Image">
                        <div class="card-body">
                            <h5 class="card-title">${news.title}</h5>
                            <p class="card-text">${news.summary || news.content}</p>
                            <a href="replit_for_Module8/news_details.html?id=${news.id}" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                `;
                newsContainer.appendChild(col);
            });
        })
        .catch(err => {
            console.error("Error loading news:", err);
        });
}

// Add event listeners
document.addEventListener("DOMContentLoaded", () => {
    loadNews();

    const searchInput = document.getElementById("searchInput");
    const categoryFilter = document.getElementById("categoryFilter");

    if (searchInput) {
        searchInput.addEventListener("input", loadNews);
    }

    if (categoryFilter) {
        categoryFilter.addEventListener("change", loadNews);
    }
});
