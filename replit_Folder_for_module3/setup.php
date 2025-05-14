<?php
    $host = "127.0.0.1";
    $user = getenv("db_user");
    $pass = getenv("db_pass");
    $db = getenv("db_name");


$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!$conn->query("CREATE DATABASE IF NOT EXISTS $db")) {
    die("Database creation failed: " . $conn->error);
}
echo "Database created or already exists<br>";

$conn->select_db($db);

$sql = "CREATE TABLE IF NOT EXISTS course_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    courseName VARCHAR(100) NOT NULL,
    professorName VARCHAR(100) NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    semester VARCHAR(50) NOT NULL,
    reviewText TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($sql)) {
    die("Table creation failed: " . $conn->error);
}
echo "Table 'course_reviews' created or already exists<br>";

// Check if table is empty
$result = $conn->query("SELECT COUNT(*) as count FROM course_reviews");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $sampleData = "INSERT INTO course_reviews (courseName, professorName, rating, semester, reviewText)
        VALUES 
        ('ITCS333 - Web Development', 'Dr. John Doe', 5, 'Fall 2024', 'Great course, very interactive, and challenging at the same time. Highly recommend!'),
        ('MATH101 - Calculus I', 'Dr. Jane Smith', 4, 'Spring 2025', 'Challenging but rewarding. Great for building strong foundational math skills.'),
        ('MGMT202 - Principles of Management', 'Dr. Alan Brown', 3, 'Spring 2025', 'The course was interesting, but the workload was a bit heavy. Overall, a good experience.')";
    if ($conn->query($sampleData)) {
        echo "Sample data inserted<br>";
    } else {
        echo "Error inserting sample data: " . $conn->error . "<br>";
    }
} else {
    echo "Table already contains data<br>";
}

echo "Setup completed successfully!";

$conn->close();
?>
