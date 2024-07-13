<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafe_otomasyon"; // Veritabanı adınızı buraya yazın

// Veritabanı bağlantısını oluşturun
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol edin
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login_type = $_POST['login_type'];

    // Kullanıcı adı ve şifreyi kontrol edin
    $stmt = $conn->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ? AND sifre = ? AND giris_turu = ?");
    $stmt->bind_param("sss", $username, $password, $login_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION[$login_type . '_logged_in'] = true;

        if ($login_type === 'admin') {
            header('Location: http://localhost/kafe/adminpanel.php');
        } elseif ($login_type === 'garson') {
            header('Location: http://localhost/kafe/garson.php');
        } elseif ($login_type === 'kasa') {
            header('Location: http://localhost/kafe/kasa.php');
        }
        exit;
    } else {
        $error = 'Kullanıcı adı veya şifre hatalı!';
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Paneli</title>
    <style>
 body {
    background-color: #128c7e; /* Darker green background */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Changed font */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    text-align: center;
    background-size: cover;
    background-position: center;
}

h1 {
    color: #fff; /* White heading color */
    font-size: 28px; /* Increased font size */
    margin-bottom: 20px; /* Added margin bottom */
}

.login-panel {
    width: 400px; /* Increased panel width */
    margin: 0 auto;
    padding: 30px; /* Increased padding */
    background-color: rgba(255, 255, 255, 0.9); /* Increased opacity */
    border-radius: 8px; /* Increased border radius */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Increased shadow */
    text-align: left;
}

label {
    color: #128c7e; /* Darker green for labels */
    font-size: 16px; /* Increased font size */
    
}

input[type="text"],
input[type="password"],
input[type="submit"] {
    width: 100%;
    padding: 12px; /* Increased padding */
    margin-top: 10px; /* Increased margin top */
    margin-bottom: 20px; /* Increased margin bottom */
    border: 2px solid #128c7e; /* Thicker border */
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px; /* Increased font size */
}

input[type="submit"] {
    background-color: #fff; /* White background for button */
    color: #128c7e; /* Darker green text color */
    border: 2px solid #128c7e; /* Darker green border */
    padding: 14px; /* Increased padding */
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease; /* Added color transition */
}

input[type="submit"]:hover {
    background-color: #128c7e; /* Darker green on hover */
    color: #fff; /* White text on hover */
}

.error {
    color: red;
    text-align: center;
    margin-bottom: 20px; /* Increased margin bottom */
    font-size: 14px; /* Decreased font size */
}
h1{
    color: black;
    
}

        
    </style>
</head>
<body>
    <div class="login-panel">
        <h1>Giriş Paneli</h1>
        <form method="POST">
            <label for="login_type">Giriş Türü:</label><br>
            <select id="login_type" name="login_type">
                <option value="admin">Admin</option>
                <option value="garson">Garson</option>
                <option value="kasa">Kasa</option>
            </select><br><br>
            <label for="username">Kullanıcı Adı:</label><br>
            <input type="text" id="username" name="username"><br><br>
            <label for="password">Şifre:</label><br>
            <input type="password" id="password" name="password"><br><br>
            <input type="submit" value="Giriş Yap">
        </form>
        <?php if (isset($error)) { ?>
            <p class="error"><?= $error ?></p>
        <?php } ?>
    </div>
</body>
</html>
