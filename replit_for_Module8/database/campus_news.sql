CREATE DATABASE IF NOT EXISTS campus_news;
USE campus_news;

-- Drop table if it already exists
DROP TABLE IF EXISTS news;

-- Create the news table
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
