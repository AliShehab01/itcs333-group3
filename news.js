document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const newsContainer = document.getElementById('newsContainer');

    function loadNews(search = '', category = '') {
        fetch(`replit_for_Module8/api/get_news.php?search=${search}&category=${category}`)
            .then(response => response.json())
            .then(data => {
                newsContainer.innerHTML = '';
                if (data.length === 0) {
                    newsContainer.innerHTML = '<div class="col-12"><p class="text-center">No news found.</p></div>';
                    return;
                }
                
                data.forEach(news => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-4';
                    col.innerHTML = `
                        <div class="card h-100">
                            <img src="${news.image_url || 'images/UoBcampus.jpg'}" class="card-img-top" alt="News Image">
                            <div class="card-body">
                                <h5 class="card-title">${news.title}</h5>
                                <p class="card-text">${news.summary}</p>
                                <p class="card-text"><small class="text-muted">Category: ${news.category}</small></p>
                                <a href="replit_for_Module8/news_details.html?id=${news.id}" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    `;
                    newsContainer.appendChild(col);
                });
            })
            .catch(err => {
                console.error('Error loading news:', err);
                newsContainer.innerHTML = '<div class="col-12"><p class="text-center text-danger">Error loading news. Please try again later.</p></div>';
            });
    }

    if (searchInput) {
        searchInput.addEventListener('input', debounce(() => {
            loadNews(searchInput.value, categoryFilter ? categoryFilter.value : '');
        }, 300));
    }

    if (categoryFilter) {
        categoryFilter.addEventListener('change', () => {
            loadNews(searchInput ? searchInput.value : '', categoryFilter.value);
        });
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Initial load
    loadNews();
});
