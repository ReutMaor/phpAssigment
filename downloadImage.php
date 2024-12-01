<?php

// URL של התמונה
$imageUrl = 'https://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg';//לבדוק אם עומד בדרישות

// שם התמונה שתישמר בשרת
$imagePath = 'default-avatar.jpg';//לשנות שם 

// הורדת התמונה
$imageData = file_get_contents($imageUrl);

// בדיקה אם ההורדה הצליחה
if ($imageData === false) {
    die("Failed to download the image.");
}

// שמירת התמונה בשרת
file_put_contents($imagePath, $imageData);

// הצגת הודעה על הצלחה
echo "Image downloaded and saved as $imagePath";
?>