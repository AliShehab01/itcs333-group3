
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const newsContainer = document.getElementById('newsContainer');

    function loadNews(search = '', category = '') {
        fetch(`/replit_for_Module8/api/get_news.php?search=${search}&category=${category}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                newsContainer.innerHTML = '';
                if (!data || data.length === 0) {
                    newsContainer.innerHTML = '<p class="text-center">No news found.</p>';
                    return;
                }
                data.forEach(news => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-4';
                    col.innerHTML = `
                        <div class="card h-100">
                            <img src="${news.image_url || '/images/UoBcampus.jpg'}" class="card-img-top" alt="News Image">
                            <div class="card-body">
                                <h5 class="card-title">${news.title}</h5>
                                <p class="card-text">${news.summary}</p>
                                <a href="/replit_for_Module8/news_details.html?id=${news.id}" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    `;
                    newsContainer.appendChild(col);
                });
            })
            .catch(err => {
                console.error('Error loading news:', err);
                newsContainer.innerHTML = '<p class="text-center text-danger">Error loading news. Please try again later.</p>';
            });
    }

    // Initial load
    loadNews();

    // Search input handler
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            loadNews(searchInput.value, categoryFilter ? categoryFilter.value : '');
        });
    }

    // Category filter handler
    if (categoryFilter) {
        categoryFilter.addEventListener('change', () => {
            loadNews(searchInput ? searchInput.value : '', categoryFilter.value);
        });
    }
});
