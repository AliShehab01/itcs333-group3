document.addEventListener('DOMContentLoaded', function () {
    const newsContainer = document.getElementById('newsContainer');

    if (!Array.isArray(newsItems)) {
        console.error('newsItems is not defined or not an array.');
        return;
    }

    newsItems.forEach(item => {
        const col = document.createElement('div');
        col.className = 'col-md-4 mb-4';

        const card = document.createElement('div');
        card.className = 'card h-100 shadow-sm';

        if (item.image) {
            const img = document.createElement('img');
            img.className = 'card-img-top';
            img.src = item.image;
            img.alt = item.title;
            card.appendChild(img);
        }

        const cardBody = document.createElement('div');
        cardBody.className = 'card-body';

        const title = document.createElement('h5');
        title.className = 'card-title';
        title.textContent = item.title;

        const content = document.createElement('p');
        content.className = 'card-text';
        content.textContent = item.content;

        cardBody.appendChild(title);
        cardBody.appendChild(content);
        card.appendChild(cardBody);
        col.appendChild(card);
        newsContainer.appendChild(col);
    });
});
