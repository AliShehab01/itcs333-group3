
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const newsContainer = document.getElementById('newsContainer');

    function loadNews(search = '', category = '') {
        fetch(`replit_for_Module8/api/get_news.php?search=${search}&category=${category}`)
            .then(response => response.json())
            .catch(err => {
                console.error('Error loading news:', err);
                // Fallback to static news if API fails
                const newsItems = [
                    {
                        title: "New IT Building Opens Next Semester",
                        content: "The University of Bahrain is proud to announce the completion of the new IT building. Students can access it starting Fall 2025.",
                        image: "images/UoBcampus.jpg"
                    },
                    {
                        title: "Career Fair 2025 Announced",
                        content: "Mark your calendars! UOB's Career Fair 2025 will be held on May 15 in the main hall with over 50 companies participating.",
                        image: "images/UoBcampus.jpg"
                    },
                    {
                        title: "UOB Ranked Among Top 50 Arab Universities",
                        content: "UOB has been ranked among the top 50 Arab universities in the latest QS rankings.",
                        image: "images/UoBcampus.jpg"
                    }
                ];

                newsContainer.innerHTML = '';
                newsItems.forEach(news => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-4';
                    col.innerHTML = `
                        <div class="card h-100">
                            <img src="${news.image}" class="card-img-top" alt="News Image">
                            <div class="card-body">
                                <h5 class="card-title">${news.title}</h5>
                                <p class="card-text">${news.content}</p>
                            </div>
                        </div>
                    `;
                    newsContainer.appendChild(col);
                });
            });
    }

    // Initial load
    loadNews();

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            loadNews(searchInput.value, categoryFilter ? categoryFilter.value : '');
        });
    }

    if (categoryFilter) {
        categoryFilter.addEventListener('change', () => {
            loadNews(searchInput ? searchInput.value : '', categoryFilter.value);
        });
    }
});
