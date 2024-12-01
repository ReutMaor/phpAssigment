<?php
require 'Database.php'; // מחבר את מחלקת ה-Database
$db = new Database();
$conn = $db->connect();
if (!$conn) {
    die("Connection failed!");
}
function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); // כתובת ה-API
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // מחזיר את התוצאה
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true); // ממיר את JSON למערך
}
// קישור לנתונים
$users = fetchData('https://jsonplaceholder.typicode.com/users');
$posts = fetchData('https://jsonplaceholder.typicode.com/posts');

// הכנסת משתמשים למסד הנתונים
foreach ($users as $user) {
   // בדיקת קיום משתמש
$userExists = $db->select("SELECT COUNT(*) as count FROM users WHERE id = ?", [$user['id']]);
if ($userExists[0]['count'] == 0) {
    // הוספת המשתמש אם לא קיים
    $db->insert(
        "INSERT INTO users (id, name, email, active) VALUES (?, ?, ?, ?)",
        [$user['id'], $user['name'], $user['email'], true]
    );
} else {
    echo "המשתמש עם מזהה " . $user['id'] . " כבר קיים.<br>";
}

}

// הכנסת פוסטים למסד הנתונים
foreach ($posts as $post) {
   // בדיקת קיום פוסט
$postExists = $db->select("SELECT COUNT(*) as count FROM posts WHERE id = ?", [$post['id']]);
if ($postExists[0]['count'] == 0) {
    // הוספת הפוסט אם לא קיים
    $db->insert(
        "INSERT INTO posts (id, user_id, title, body, created_at, active) VALUES (?, ?, ?, ?, NOW(), ?)",
        [$post['id'], $post['userId'], $post['title'], $post['body'], true]
    );
} else {
    echo "הפוסט עם מזהה " . $post['id'] . " כבר קיים.<br>";
}

}

echo "Data fetched and inserted successfully!";
?>