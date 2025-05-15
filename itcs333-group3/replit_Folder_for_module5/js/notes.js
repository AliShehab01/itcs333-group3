// Notes Module JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the notes display
    loadNotes();
    
    // Set up event listeners
    setupEventListeners();
    
    // Initialize file input display
    initFileInput();
});

// Load notes from the API
function loadNotes(filters = {}) {
    // Build query string from filters
    const queryParams = new URLSearchParams();
    
    if (filters.college) queryParams.append('college', filters.college);
    if (filters.year) queryParams.append('year', filters.year);
    if (filters.semester) queryParams.append('semester', filters.semester);
    if (filters.type) queryParams.append('type', filters.type);
    if (filters.search) queryParams.append('search', filters.search);
    
    // Show loading state
    const notesContainer = document.getElementById('notesContainer') || document.querySelector('.row:has(.col-md-4)');
    notesContainer.innerHTML = '<div class="col-12 text-center"><p>Loading notes...</p></div>';
    
    // Fetch notes from API
    fetch(`api/get_notes.php?${queryParams.toString()}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayNotes(data.data);
            } else {
                notesContainer.innerHTML = `<div class="col-12 text-center"><p>Error: ${data.message}</p></div>`;
            }
        })
        .catch(error => {
            console.error('Error fetching notes:', error);
            notesContainer.innerHTML = '<div class="col-12 text-center"><p>Failed to load notes. Please try again later.</p></div>';
        });
}

// Display notes in the UI
function displayNotes(notes) {
    const notesContainer = document.getElementById('notesContainer') || document.querySelector('.row:has(.col-md-4)');
    
    // Clear container
    notesContainer.innerHTML = '';
    
    if (notes.length === 0) {
        notesContainer.innerHTML = '<div class="col-12 text-center"><p>No notes found matching your criteria.</p></div>';
        return;
    }
    
    // Create note cards
    notes.forEach(note => {
        const noteCard = document.createElement('div');
        noteCard.className = 'col-md-4 mb-4';
        noteCard.innerHTML = `
            <div class="card shadow-sm h-100" data-note-id="${note.id}">
                <div class="card-body">
                    <h5 class="card-title">${note.subject_code} - ${note.title}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">${note.type}</h6>
                    <p class="card-text">${note.description || 'No description provided.'}</p>
                    <span class="badge badge-primary mb-2">${note.college}</span>
                    ${note.semester ? `<span class="badge badge-secondary mb-2">${note.semester}</span>` : ''}
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">Uploaded: ${new Date(note.upload_date).toLocaleDateString()}</small>
                        <div>
                            <a href="api/download_note.php?id=${note.id}" class="btn btn-sm btn-outline-primary">Download</a>
                            <button class="btn btn-sm btn-outline-danger delete-note">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        notesContainer.appendChild(noteCard);
    });
    
    // Add event listeners to delete buttons
    document.querySelectorAll('.delete-note').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const noteId = this.closest('.card').dataset.noteId;
            if (confirm('Are you sure you want to delete this note?')) {
                deleteNote(noteId);
            }
        });
    });
}

// Set up event listeners
function setupEventListeners() {
    // Form submission
    const notesForm = document.getElementById('notesForm');
    if (notesForm) {
        notesForm.addEventListener('submit', function(e) {
            e.preventDefault();
            uploadNote(this);
        });
    }
    
    // Search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            applyFilters();
        }, 500));
    }
    
    // Filter selects
    const filterSelects = [
        document.getElementById('collegeFilter'),
        document.getElementById('yearFilter'),
        document.getElementById('semesterFilter'),
        document.getElementById('typeFilter')
    ];
    
    filterSelects.forEach(select => {
        if (select) {
            select.addEventListener('change', function() {
                applyFilters();
            });
        }
    });
}

// Apply filters and search
function applyFilters() {
    const filters = {
        college: document.getElementById('collegeFilter')?.value || '',
        year: document.getElementById('yearFilter')?.value || '',
        semester: document.getElementById('semesterFilter')?.value || '',
        type: document.getElementById('typeFilter')?.value || '',
        search: document.getElementById('searchInput')?.value || ''
    };
    
    loadNotes(filters);
}

// Upload a new note
function uploadNote(form) {
    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    submitBtn.textContent = 'Adding...';
    submitBtn.disabled = true;
    
    fetch('api/add_note.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Show success message
            alert('Note added successfully!');
            
            // Reset form
            form.reset();
            
            // Reload notes
            loadNotes();
        } else {
            alert(`Error: ${data.message}`);
        }
    })
    .catch(error => {
        console.error('Error adding note:', error);
        alert('Failed to add note. Please try again later.');
    })
    .finally(() => {
        // Reset button
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    });
}

// Delete a note
function deleteNote(noteId) {
    fetch('api/delete_note.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: noteId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Show success message
            alert('Note deleted successfully!');
            
            // Reload notes
            loadNotes();
        } else {
            alert(`Error: ${data.message}`);
        }
    })
    .catch(error => {
        console.error('Error deleting note:', error);
        alert('Failed to delete note. Please try again later.');
    });
}

// Initialize file input display
function initFileInput() {
    const fileInput = document.getElementById('notesFile');
    const fileLabel = document.querySelector('.custom-file-label');
    
    if (fileInput && fileLabel) {
        fileInput.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'Choose file';
            fileLabel.textContent = fileName;
        });
    }
}

// Utility function for debouncing
function debounce(func, delay) {
    let timeout;
    return function() {
        const context = this;
        const args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), delay);
    };
}
