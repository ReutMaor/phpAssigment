<?php
require 'Database.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Connection to database failed!");
}

// הוספת עמודה 'birth_date' לטבלת 'users' אם היא לא קיימת
$addBirthDateColumn = "
    ALTER TABLE users 
    ADD COLUMN birth_date DATE NULL;
";

try {
    $conn->exec($addBirthDateColumn);
    echo "Column 'birth_date' added successfully!";
} catch (PDOException $e) {
    if ($e->getCode() == '42S21') {
        echo "Column 'birth_date' already exists.";
    } else {
        echo "Error adding column 'birth_date': " . $e->getMessage();
    }
}


$query = "
    SELECT 
        p.*, u.name AS user_name, u.email AS user_email
    FROM 
        posts p
    JOIN 
        users u ON p.user_id = u.id
    WHERE 
        MONTH(u.birth_date) = MONTH(CURDATE())
        AND p.created_at = (
            SELECT MAX(created_at) 
            FROM posts 
            WHERE user_id = u.id
        )
    ORDER BY 
        p.created_at DESC
";

$results = $db->select($query);
?>

<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <title>Last posts of users that had Birthday this month</title>
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
    <h1>Last posts of users that had Birthday this month</h1>

    <?php if (!empty($results)): ?>
        <?php foreach ($results as $result): ?>
            <div class="user">
                <img src="default-avatar.jpg" alt="תמונת משתמש">
                <h2><?= htmlspecialchars($result['user_name']) ?> (<?= htmlspecialchars($result['user_email']) ?>)</h2>
                <div class="post">
                    <h3><?= htmlspecialchars($result['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($result['body'])) ?></p>
                    <small>published in :<?= htmlspecialchars($result['created_at']) ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>There's no posts that answear this request</p>
    <?php endif; ?>
</body>
</html>
