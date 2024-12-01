<?php
require 'Database.php'; // טוען את מחלקת החיבור למסד הנתונים

// יצירת אובייקט של Database
$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Connection to database failed!");
}

// יצירת טבלת 'users'
$createUsersTable = "
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    active BOOLEAN NOT NULL DEFAULT true
);

";

$conn->exec($createUsersTable);

// יצירת טבלת 'posts'
$createPostsTable = "
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    active BOOLEAN NOT NULL DEFAULT true,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

";

$conn->exec($createPostsTable);

echo "Tables created successfully!";
?>
