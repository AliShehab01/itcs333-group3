let studyGroups = [];
async function fetchStudyGroups() {
    const resultContainor = document.getElementById("resultSearch");
    resultContainor.innerHTML = "<p>Loading...</p>";

    // Load saved local groups
    const localGroups = JSON.parse(localStorage.getItem("studyGroups")) || [];

    try {
        // Fetch from API
        const response = await fetch('https://mockapi.io/api/your-endpoint');
        const apiData = response.ok ? await response.json() : [];

        // Merge both
        studyGroups = [...apiData, ...localGroups];
        displayGroups();
    } catch (error) {
        console.error('Error fetching study groups:', error);
        studyGroups = localGroups; // fallback to local only
        displayGroups();
    }
}
fetchStudyGroups(); // Call the function to fetch and display study groups

// Function to add a new study group
document.getElementById("groupForm")
    .addEventListener("submit", function (event) {
        event.preventDefault();

        const subjectCode = document.getElementById("subject").value.trim();
        const section = document.getElementById("section").value.trim();
        const college = document.getElementById("college").value.trim();
        const wlink = document.querySelector("input[type='url']").value.trim();

        if (!subjectCode || !section || !college || !wlink) {
            alert("Please fill all the fields.");
            return;
        }

        const newGroup = { subjectCode, section, college, wlink };

        // Add to the array
        studyGroups.push(newGroup);

        // Save to localStorage
        const localGroups = JSON.parse(localStorage.getItem("studyGroups")) || [];
        localGroups.push(newGroup);
        localStorage.setItem("studyGroups", JSON.stringify(localGroups));

        document.getElementById("groupForm").reset();
        alert("Study group created successfully!");
        displayGroups(currentPage);
    });


document.getElementById("searchInput").addEventListener("input", function () {
    const searchval = this.value.trim().toUpperCase();
    const results = studyGroups.filter((group) =>
        group.subjectCode.toUpperCase().startsWith(searchval) ||
        group.college.toUpperCase().includes(searchval) // Add filter by college
    );

    displaySearchResults(results);
});

function displaySearchResults(results) {
    const resultContainor = document.getElementById("resultSearch");
    resultContainor.innerHTML = "";

    if (results.length === 0) {
        resultContainor.innerHTML = "<p>No group found.</p>";
        return;
    }

    results.forEach((group) => {
        const groupElement = document.createElement("div");
        groupElement.classList.add("card", "p-3", "mb-3");
        groupElement.innerHTML = `
            <h5>${group.title} - Section ${group.id}</h5>
            <p><strong>College:</strong> User ${group.userId}</p>
            <p><a href="#" target="_blank">Join WhatsApp Group (Simulated)</a></p>
        `;
        resultContainor.appendChild(groupElement);
    });
}



function displayGroups() {
    const resultContainor = document.getElementById("resultSearch");
    resultContainor.innerHTML = "";
    if (studyGroups.length === 0) {
        resultContainor.innerHTML = "<p>No group found.</p>";
        return;
    }
    studyGroups.forEach((group) => {
        const groupElement = document.createElement("div");
        groupElement.classList.add("card", "p-3", "mb-3");
        groupElement.innerHTML = `
            <h5>${group.title} - Section ${group.id}</h5>
            <p><strong>College:</strong> User ${group.userId}</p>
            <p><a href="#" target="_blank">Join WhatsApp Group (Simulated)</a></p>
        `;
        resultContainor.appendChild(groupElement);
    });
}


//pagination
let currentPage = 1;
const itemsPerPage = 5;

function paginate(array, page = 1) {
    const start = (page - 1) * itemsPerPage;
    return array.slice(start, start + itemsPerPage);
}

function displayGroups(page = 1) {
    const resultContainor = document.getElementById("resultSearch");
    resultContainor.innerHTML = "";

    const paginatedData = paginate(studyGroups, page);

    if (paginatedData.length === 0) {
        resultContainor.innerHTML = "<p>No group found.</p>";
        return;
    }

    paginatedData.forEach(group => {
        const groupElement = document.createElement("div");
        groupElement.classList.add("card", "p-3", "mb-3");
        groupElement.innerHTML = `
            <h5>${group.subjectCode} - Section ${group.section}</h5>
            <p><strong>College:</strong> ${group.college}</p>
            <p><a href="${group.wlink}" target="_blank">Join WhatsApp Group</a></p>
        `;
        resultContainor.appendChild(groupElement);
    });

    updatePaginationControls();
}

function updatePaginationControls() {
    const totalPages = Math.ceil(studyGroups.length / itemsPerPage);
    const paginationContainer = document.querySelector(".pagination");
    paginationContainer.innerHTML = `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a>
        </li>
        ${Array.from({ length: totalPages }, (_, i) => `
            <li class="page-item ${i + 1 === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i + 1})">${i + 1}</a>
            </li>`).join('')}
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a>
        </li>
    `;
}

function changePage(page) {
    currentPage = page;
    displayGroups(page);
}
