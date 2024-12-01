<?php
require 'Database.php'; // טוען את מחלקת החיבור למסד הנתונים

// התחברות למסד הנתונים
$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Connection to database failed!");
}

// שליפת משתמשים פעילים עם הפוסטים שלהם
$query = "
    SELECT 
        users.id AS user_id, 
        users.name AS user_name, 
        users.email AS user_email, 
        posts.title AS post_title, 
        posts.body AS post_body 
    FROM 
        users 
    JOIN 
        posts 
    ON 
        users.id = posts.user_id 
    WHERE 
        users.active = 1 AND posts.active = 1
";
$results = $db->select($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .user {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .user img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .post {
            margin-top: 10px;
            padding-left: 10px;
        }
    </style>
</head>
<body>
    <h1>Active Users and Their Posts</h1>

    <?php foreach ($results as $result): ?>
        <div class="user">
            <img src="default-avatar.jpg" alt="User Avatar">
            <h2><?= htmlspecialchars($result['user_name']) ?> (<?= htmlspecialchars($result['user_email']) ?>)</h2>
            <div class="post">
                <h3><?= htmlspecialchars($result['post_title']) ?></h3>
                <p><?= htmlspecialchars($result['post_body']) ?></p>
            </div>
        </div>
    <?php endforeach; ?>

</body>
</html>
