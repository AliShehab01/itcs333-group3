# Notes Module Documentation

This document explains the SQL schema and CRUD operations implementation for the Notes module in the Campus Hub application.

## Database Schema

The database schema for the Notes module is defined in `database/schema.sql`. It creates a `notes` table with the following structure:

```sql
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_code VARCHAR(10) NOT NULL,
    college VARCHAR(100) NOT NULL,
    title VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    download_count INT DEFAULT 0,
    semester VARCHAR(50),
    year VARCHAR(20)
);
```

## API Endpoints

The Notes module includes the following PHP API endpoints:

1. **config.php** - Database connection configuration
2. **get_notes.php** - Retrieves notes with optional filtering
3. **add_note.php** - Uploads a new note with file attachment
4. **update_note.php** - Updates an existing note
5. **delete_note.php** - Deletes a note and its associated file
6. **download_note.php** - Downloads a note file and increments the download counter

## JavaScript Integration

The `js/notes.js` file provides client-side functionality for:

- Loading and displaying notes
- Filtering notes by college, year, semester, and type
- Searching notes by subject code, title, or description
- Uploading new notes
- Deleting notes
- Handling file uploads

## Setup Instructions

1. **Database Setup**:
   - Import the schema by running the SQL script in `database/schema.sql`
   - Update database credentials in `api/config.php` if needed

2. **File Structure**:
   - Ensure the `uploads` directory exists (it will be created automatically when the first note is uploaded)
   - Make sure the `uploads` directory has write permissions

3. **Integration**:
   - The Notes module is already integrated into `indexModule5.html`
   - The JavaScript file is loaded at the bottom of the HTML file

## Usage

- **Browsing Notes**: Notes are displayed in card format with filtering options
- **Uploading Notes**: Use the form to upload new notes with metadata
- **Downloading Notes**: Click the "Download" button on any note card
- **Deleting Notes**: Click the "Delete" button on any note card

## Security Considerations

- File uploads are restricted to PDF, DOC, DOCX, PPT, and PPTX formats
- Maximum file size is limited to 10MB
- All user inputs are sanitized to prevent SQL injection
- Prepared statements are used for all database operations
