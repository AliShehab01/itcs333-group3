 //Fetches study groups from the PHP API, displays them with pagination,
 // handles search input, and submits new groups via JSON POST.

const API_URL = 'api.php';
let studyGroups = [];
let currentPage = 1;
const ITEMS_PER_PAGE = 5;

// Helpers
/*
 * Show a temporary message to the user.
 * @param {string} msg 
 * @param {'info'|'success'|'danger'} type 
 */
function showMessage(msg, type = 'info') {
  const container = document.getElementById('message-container');
  if (!container) return;
  container.innerHTML = `
    <div class="alert alert-${type} alert-dismissible">
      ${msg}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  `;
  container.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Map API’s snake_case fields to camelCase.
 * @param {Object[]} rawGroups 
 * @returns {Object[]}
 */
function normalizeGroups(rawGroups) {
  return rawGroups.map(g => ({
    id:          g.id,
    subjectCode: g.subject_code,
    section:     g.section,
    college:     g.college,
    wlink:       g.wlink
  }));
}




//Fetch all study groups from the API.

async function fetchStudyGroups() {
  const container = document.getElementById('resultSearch');
  container.innerHTML = '<p>Loading…</p>';

  try {
    const res = await fetch(API_URL);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const raw = await res.json();
    studyGroups = normalizeGroups(raw);
    renderGroups(currentPage);
  } catch (err) {
    console.error('Fetch error:', err);
    container.innerHTML = '<p>Unable to load groups.</p>';
  }
}

// ——————————————
// Rendering & Pagination
// ——————————————
/**
 * Paginate an array.
 * @param {any[]} array 
 * @param {number} page 
 */
function paginate(array, page = 1) {
  const start = (page - 1) * ITEMS_PER_PAGE;
  return array.slice(start, start + ITEMS_PER_PAGE);
}

/**
 * Render the current page of groups.
 * @param {number} page 
 */
function renderGroups(page = 1) {
  currentPage = page;
  const container = document.getElementById('resultSearch');
  container.innerHTML = '';
  const pageData = paginate(studyGroups, page);

  if (pageData.length === 0) {
    container.innerHTML = '<p>No group found.</p>';
  } else {
    pageData.forEach(g => {
      const card = document.createElement('div');
      card.className = 'card p-3 mb-3';
      card.innerHTML = `
        <h5>${g.subjectCode} – Section ${g.section}</h5>
        <p><strong>College:</strong> ${g.college}</p>
        <p><a href="${g.wlink}" target="_blank">Join WhatsApp Group</a></p>
      `;
      container.appendChild(card);
    });
  }

  updatePagination();
}

/**
 * Update pagination controls beneath the list.
 */
function updatePagination() {
  const totalPages = Math.ceil(studyGroups.length / ITEMS_PER_PAGE);
  const nav = document.querySelector('.pagination');
  nav.innerHTML = `
    <li class="page-item ${currentPage===1?'disabled':''}">
      <a class="page-link" href="#" onclick="renderGroups(${currentPage-1})">Previous</a>
    </li>
    ${Array.from({length: totalPages}, (_, i) => `
      <li class="page-item ${i+1===currentPage?'active':''}">
        <a class="page-link" href="#" onclick="renderGroups(${i+1})">${i+1}</a>
      </li>`).join('')}
    <li class="page-item ${currentPage===totalPages?'disabled':''}">
      <a class="page-link" href="#" onclick="renderGroups(${currentPage+1})">Next</a>
    </li>
  `;
}

// ——————————————
// Search
// ——————————————
/**
 * Filter the in-memory list by subjectCode or college.
 * @param {string} term 
 */
function applySearch(term) {
  const val = term.trim().toUpperCase();
  const filtered = studyGroups.filter(g =>
    g.subjectCode.toUpperCase().startsWith(val) ||
    g.college.toUpperCase().includes(val)
  );
  renderFiltered(filtered);
}

/**
 * Render only a filtered subset (with pagination).
 * @param {Object[]} results 
 */
function renderFiltered(results) {
  studyGroups = results;       // temporarily override
  renderGroups(1);
}


// Form Submission
// Handle the “Add Group” form post.

async function handleFormSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const newGroup = {
    subjectCode: form.subject.value.trim(),
    section:     form.section.value.trim(),
    college:     form.college.value.trim(),
    wlink:       form.querySelector('input[type="url"]').value.trim()
  };

  if (Object.values(newGroup).some(v => !v)) {
    showMessage('Please fill all fields.', 'danger');
    return;
  }

  try {
    const res = await fetch(API_URL, {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify(newGroup)
    });
    const json = await res.json();
    if (json.success) {
      showMessage('Group added successfully!', 'success');
      form.reset();
      // Re-fetch to get the new entry (or just push/refresh)
      fetchStudyGroups();
    } else {
      showMessage(json.error || 'Insert failed.', 'danger');
    }
  } catch (err) {
    console.error('Save error:', err);
    showMessage('Error saving study group.', 'danger');
  }
}

// Initialization

function initModule() {
  document.getElementById('searchInput')
          .addEventListener('input', e => applySearch(e.target.value));
  document.getElementById('groupForm')
          .addEventListener('submit', handleFormSubmit);

  fetchStudyGroups();
}

document.addEventListener('DOMContentLoaded', initModule);
//for reg and sign in
function attachProtectedLinks() {
  document.querySelectorAll('a#cksign').forEach(link => {
    link.addEventListener('click', event => {
      event.preventDefault();
      fetch('check_session.php')
        .then(r => r.json())
        .then(data => {
          if (data.loggedIn) {
            window.location.href = link.href;
          } else {
            alert('Please sign in or register first.');
            window.location.href = 'login.php';
          }
        })
        .catch(err => {
          console.error('Session check error:', err);
          alert('Could not verify session; try again later.');
        });
    });
  });
}

// — Initialization ——————————————————————————————————————————
document.addEventListener('DOMContentLoaded', () => {
  console.log('🟢 DOM loaded, initializing…');
  // 1) Study‐groups setup
  document.getElementById('searchInput')
    .addEventListener('input', e => applySearch(e.target.value));
  document.getElementById('groupForm')
    .addEventListener('submit', handleFormSubmit);
  fetchStudyGroups();

  // 2) Attach session‐guard on <a id="cksign">
  attachProtectedLinks();
});