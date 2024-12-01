<?php
class Database { //הכרזת שם המחלקה
    //פרייבט-הגדרת ואתחול המשתנים נגישים רק בתוך המחלקה
    private $host = 'localhost';//כתובת השרת של מסד הנתונים יחזיק
    private $dbname = 'my_database';//שם מסד הנתונים-לשנות בהתאם
    private $username = 'root';//יחזיק שם המשתמש של מסד הנתונים ,כרגע שם ברירת מחדל
    private $password = '';//כרגע ריק ברירת מחדל,יחזיק סיסמת מסד הנתונים 
    private $conn;//משתנה שישמור את החיבור למסד הנתונים ברגע שנבצע את הפעולה.
//דיס -פנייה למשתנים של המחלקה הזו
    public function connect() {
        try {//       בלוק ניסיון. אם מתרחשת שגיאה, היא נתפסת בבלוק קאצ
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);//  יוצר חיבור למסד הנתונים באמצעות מחלקת פיאודי ע"י ציון סוג מסד הנתונים כתובת השרת ושם מסד הנתונים שם המשתמש וסיסמר
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//מגדיר ש-PDO ישליך שגיאות כ-exceptions.
            return $this->conn;//מחזיר את החיבור למסד
        } catch (PDOException $e) {//תופס שגיאות חיבור.
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }

    public function select($query, $params = []) {// מתודה לביצוע שאילתות בחר מקבלת שאילתא ופרמטרים לשאילתא אם יש
        $stmt = $this->conn->prepare($query);//מכין את השאילתה להרצה
        $stmt->execute($params);//מבצע את השאילתה עם הפרמטרים
        return $stmt->fetchAll(PDO::FETCH_ASSOC);//מחזיר את כל השורות כתוצאה, בפורמט של מערך אסוציאטיבי (כל עמודה במפתח משלה
    }

    public function insert($query, $params = []) {//מתודה לביצוע שאילתות הכנס
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function update($query, $params = []) {//מתודה לביצוע שאילתות עדכון
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function delete($query, $params = []) {//מתודה לביצוע שאילתות מחיקה
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }
}
?>