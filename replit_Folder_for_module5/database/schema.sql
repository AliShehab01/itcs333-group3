-- SQL Schema for Notes Module

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS campus_hub;

USE campus_hub;

-- Notes table
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

-- Create indexes for better performance
CREATE INDEX idx_subject ON notes(subject_code);
CREATE INDEX idx_college ON notes(college);
CREATE INDEX idx_type ON notes(type);
