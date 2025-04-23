let studyGroups = [];
// Function to add a new study group
document.getElementById("groupForm")
    .addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the form from submitting normally
        const subjectCode = document.getElementById("subject").value.trim();
        const section = document.getElementById("section").value.trim();
        const college = document.getElementById("college").value.trim();
        const wlink = document.querySelector("input[type='url']").value.trim();
        //Validation 
        if (subjectCode === "" || section === "" || college === "" || wlink === "") {
            alert("Please fill all the fields.");
            return;
        }
        // object for the study group creation 
        const newGroup = {
            subjectCode,
            section,
            college,
            wlink,
        };
        studyGroups.push(newGroup); // save the new group to the array

        document.getElementById("groupForm").reset(); // to clear the from the previous data
        alert("Study group created successfully!");
        displayGroups(); // function to display the new group in the table
    }
    );

document.getElementById("searchInput").addEventListener("input", function () {
    const searchval = this.value.trim().toUpperCase();
    const results = studyGroups.filter((group) =>
        group.subjectCode.toUpperCase().startsWith(searchval)
    );

    displaySearchResults(results);
});

function displaySearchResults(results) {
    const resultContainor = document.getElementById("resultSearch");
    resultContainor.innerHTML = ""; // Clear previous results

    if (results.length === 0) {
        resultContainor.innerHTML = "<p>No group found.</p>";
        return;
    }

    results.forEach((group) => {
        const groupElement = document.createElement("div");
        groupElement.classList.add("card", "p-3", "mb-3");
        groupElement.innerHTML = `
            <h5>${group.subjectCode} - Section ${group.section}</h5>
            <p><strong>College:</strong> ${group.college}</p>
            <p><a href="${group.wlink}" target="_blank">Join WhatsApp Group</a></p>
        `;
        resultContainor.appendChild(groupElement);
    });
}


function displayGroups() {
    const resultContainor = document.getElementById("resultSearch");
    resultContainor.innerHTML = ""; // clear the previous results
    if (studyGroups.length === 0) {
        resultContainor.innerHTML = "<p>No group found.</p>";
        return;
    }
    studyGroups.forEach((group) => {
        const groupElement = document.createElement("div");
        groupElement.classList.add("card", "p-3", "mb-3");
        groupElement.innerHTML = `
            <h5>${group.subjectCode} - Section ${group.section}</h5>
            <p><strong>College:</strong> ${group.college}</p>
            <p><a href="${group.wlink}" target="_blank">Join WhatsApp Group</a></p>
        `;
        resultContainor.appendChild(groupElement);
    });
}
