let Books = [];
let currentPage = 1;
const BooksPerPage = 6;

// Random images array
const bookImages = [
  'images/Physics.jpg',
  'images/Chemistry.png',
  'images/Biology.jpeg',
  'images/Economics.jpg',
  'images/Programming.png',
];

// Fetch student marketplace data
async function fetchBooks() {
  const BooksContainer = document.getElementById("books-list");
  BooksContainer.innerHTML = '<div class="text-center w-100">Loading Books...</div>';

  try {
    const response = await fetch("https://jsonplaceholder.typicode.com/posts?_limit=30");
    if (!response.ok) throw new Error("Network error");

    const data = await response.json();

    Books = data.map(post => ({
        code: generateRandomCode(),
        title: post.title,
        description: post.body,
        price: Math.floor(Math.random() * 50) + 5,
        condition: ['Good', 'Used', 'Like New', 'Slightly Used'][Math.floor(Math.random() * 4)],
        pickup: ['pickup on campus','free delivery','fast delivery'][Math.floor(Math.random() * 3)],
        seller: `3${Math.floor(10000000 + Math.random() * 89999999)}`,
        image: bookImages[Math.floor(Math.random() * bookImages.length)]
    }));

    displayBooks();
  } catch (error) {
    BooksContainer.innerHTML = '<div class="text-danger w-100">Failed to load Books. Please try again later.</div>';
    console.error("Error fetching Books:", error);
  }
}

// Display Books with pagination
function displayBooks(page = 1) {
  const BooksContainer = document.getElementById("books-list");
  BooksContainer.innerHTML = "";

  const start = (page - 1) * BooksPerPage;
  const paginated = Books.slice(start, start + BooksPerPage);

  if (paginated.length === 0) {
    BooksContainer.innerHTML = '<div class="text-center w-100">No Books found.</div>';
    return;
  }

  paginated.forEach(book => {
    const card = document.createElement("div");
    card.className = "col-md-4 mb-4";
    card.innerHTML = `
        <div class="card shadow-sm h-100">
            <img src="${book.image}" class="card-img-top" alt="${book.title}">
            <div class="card-body">
                <h5 class="card-title"><strong>${book.code}:</strong> ${book.title}</h5>
                <p class="card-text"><strong>${book.price} BHD</strong> - ${book.condition}, ${book.pickup}</p>
                <a href="tel:${book.seller}" class="btn btn-primary">Contact Seller</a>
            </div>
        </div>
    `;
    BooksContainer.appendChild(card);
  });

  updatePaginationControls();
}

// Pagination controls
function updatePaginationControls() {
  const pagination = document.querySelector(".pagination");
  const totalPages = Math.ceil(Books.length / BooksPerPage);

  pagination.innerHTML = `
    <li class="page-item ${currentPage === 1 ? "disabled" : ""}">
      <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a>
    </li>
    ${Array.from({ length: totalPages }, (_, i) => `
      <li class="page-item ${currentPage === i + 1 ? "active" : ""}">
        <a class="page-link" href="#" onclick="changePage(${i + 1})">${i + 1}</a>
      </li>
    `).join("")}
    <li class="page-item ${currentPage === totalPages ? "disabled" : ""}">
      <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a>
    </li>
  `;
}

function changePage(page) {
  if (page < 1 || page > Math.ceil(Books.length / BooksPerPage)) return;
  currentPage = page;
  displayBooks(page);
}

// Search Books
function setupSearch() {
  const input = document.createElement("input");
  input.className = "form-control mb-4";
  input.placeholder = "Search Books...";

  const container = document.querySelector(".container.mt-5");
  container.insertBefore(input, container.children[1]);

  input.addEventListener("input", function () {
    const query = this.value.toLowerCase();
    const filtered = Books.filter(book =>
      book.title.toLowerCase().includes(query) ||
      book.code.toLowerCase().includes(query)
    );
    displayFilteredBooks(filtered);
  });
}

function displayFilteredBooks(filtered) {
  const BookContainer = document.getElementById("books-list");
  BookContainer.innerHTML = "";

  if (filtered.length === 0) {
    BookContainer.innerHTML = '<div class="text-center w-100">No matching items found.</div>';
    return;
  }

  filtered.forEach(book => {
    const card = document.createElement("div");
    card.className = "col-md-4 mb-4";
    card.innerHTML = `
      <div class="card shadow-sm h-100">
          <img src="${book.image}" class="card-img-top" alt="${book.title}">
          <div class="card-body">
              <h5 class="card-title"><strong>${book.code}:</strong> ${book.title}</h5>
              <p class="card-text"><strong>${book.price} BHD</strong> - ${book.condition}, ${book.pickup}</p>
              <a href="tel:${book.seller}" class="btn btn-primary">Contact Seller</a>
          </div>
      </div>
    `;
    BookContainer.appendChild(card);
  });

  document.querySelector(".pagination").innerHTML = "";
}

// Sort Books
function setupSort() {
  const select = document.createElement("select");
  select.className = "form-control mb-4";
  select.innerHTML = `
    <option value="">Sort by...</option>
    <option value="high">Price: High to Low</option>
    <option value="low">Price: Low to High</option>
  `;

  const container = document.querySelector(".container.mt-5");
  container.insertBefore(select, container.children[2]);

  select.addEventListener("change", function () {
    if (this.value === "high") {
      Books.sort((a, b) => b.price - a.price);
    } else if (this.value === "low") {
      Books.sort((a, b) => a.price - b.price);
    }
    displayBooks(1);
  });
}

// Generating a random course code
function generateRandomCode() {
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const digits = "0123456789";
    let code = "";
    for (let i = 0; i < 4; i++) {
      code += letters.charAt(Math.floor(Math.random() * letters.length));
    }
    for (let i = 0; i < 3; i++) {
      code += digits.charAt(Math.floor(Math.random() * digits.length));
    }  
    return code;
}
  
// Form validation
function setupFormValidation() {
    const form = document.getElementById("BookForm");
  
    form.addEventListener("submit", function (e) {
      e.preventDefault();
  
      const code = document.getElementById("code").value.trim();
      const title = document.getElementById("title").value.trim();
      const price = parseFloat(document.getElementById("price").value.trim());
      const seller = document.getElementById("seller").value.trim();
      const image = document.getElementById("image").value.trim();
      const condition = document.getElementById("condition").value;
      const pickup = document.getElementById("pickup").value;
  
      const codeRegex = /^[A-Z]{4}\d{3}$/;
      const sellerRegex = /^3\d{7}$/;
      const imageRegex = /\.(jpg|jpeg|png|gif)$/i
      
      if (!codeRegex.test(code)) {
        alert("Course code must be 4 uppercase letters followed by 3 digits (ex.ITCS333)");
        return;
      }
  
      if (isNaN(price) || price <= 0) {
        alert("Please enter a valid price");
        return;
      }
  
      if (!imageRegex.test(image)) {
        alert("Image must be a valid file type (.jpg, .jpeg, .png, .gif)");
        return;
      }

      if (!sellerRegex.test(seller)) {
        alert("Contact number must start with 3 and be 8 digits long (ex.32345678)");
        return;
      }

  
      const newBook = {
        code,
        title,
        price,
        condition,
        pickup,
        seller,
        image
      };
  
      Books.unshift(newBook);
      form.reset();
      displayBooks(1);

      const modalElement = document.getElementById('bookModal');
      const modal = bootstrap.Modal.getInstance(modalElement);
      modal.hide();

      alert("Book listed successfully!");
    });
}  

// Initialization
fetchBooks();
setupSearch();
setupSort();
setupFormValidation();
