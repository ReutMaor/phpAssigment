<?php
require 'Database.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Connection to database failed!");
}

$query = "
    SELECT
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS post_hour,
        COUNT(*) AS post_count
    FROM
        posts
    GROUP BY
        post_hour
    ORDER BY
        post_hour DESC;
";

$results = $db->select($query);
?>

<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <title>Amount of posts that published at that time</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Amount of posts that published at that time</h1>

    <?php if (!empty($results)): ?>
        <table>
            <thead>
                <tr>
                    <th>Date and time</th>
                    <th>Amount of posts</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['post_hour']) ?></td>
                        <td><?= htmlspecialchars($row['post_count']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>there's no data </p>
    <?php endif; ?>
</body>
</html>
