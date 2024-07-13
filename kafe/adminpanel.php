<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: http://localhost/cafe_otomasyon/login.php');
    exit;
}

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

// Yeni garson ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_garson'])) {
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $giris_turu = 'garson';

    // Yeni garson ekle
    $stmt = $conn->prepare("INSERT INTO kullanicilar (kullanici_adi, sifre, giris_turu) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $new_username, $new_password, $giris_turu);

    if ($stmt->execute()) {
        $success = "Yeni garson başarıyla eklendi!";
    } else {
        $error = "Garson eklenirken bir hata oluştu!";
    }

    $stmt->close();
}

// Fiyat güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_price'])) {
    $item_id = $_POST['item_id'];
    $new_price = $_POST['new_price'];

    // Fiyatı güncelle
    $stmt = $conn->prepare("UPDATE menu_items SET price = ? WHERE id = ?");
    $stmt->bind_param("di", $new_price, $item_id);

    if ($stmt->execute()) {
        $update_success = "Fiyat başarıyla güncellendi!";
    } else {
        $update_error = "Fiyat güncellenirken bir hata oluştu!";
    }

    $stmt->close();
}

// Tüm menü öğelerini alın
$items_result = $conn->query("SELECT * FROM menu_items");

$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Yeni font */
    background-color: #128c7e; /* Açık gri arka plan rengi */
    margin: 0;
    padding: 0;
}

h1 {
    color: #075e54; /* Koyu yeşil başlık rengi */
    text-align: center;
    margin-bottom: 30px; /* Artırılmış alt boşluk */
    font-size: 24px; /* Küçük başlık font boyutu */
}

.admin-panel {
    width: 350px;
    margin: 50px auto; /* Yükseklik ve genişlik merkezde */
    padding: 30px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center; /* Merkezi hizalama */
}

label {
    color: #075e54; /* Koyu yeşil metin rengi */
    font-size: 16px; /* Font boyutu artırıldı */
}

input[type="text"],
input[type="password"],
input[type="number"],
input[type="submit"] {
    width: 100%;
    padding: 12px; /* Artırılmış padding */
    margin-top: 10px;
    margin-bottom: 20px;
    border: 2px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

input[type="submit"] {
    background-color: #075e54; /* Koyu yeşil buton rengi */
    color: #fff; /* Beyaz yazı rengi */
    border: none;
    padding: 14px; /* Artırılmış padding */
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #128c7e; /* Hafif daha koyu hover rengi */
}

.message {
    color: #dc143c; /* Kırmızı hata mesajı rengi */
    text-align: center;
    margin-bottom: 10px;
}

.success {
    color: #008000; /* Yeşil başarı mesajı rengi */
}

    </style>
</head>
<body>
    <div class="admin-panel">
        <h1>Admin Paneli</h1>
        <form method="POST">
            <label for="new_username">Yeni Garson Kullanıcı Adı:</label><br>
            <input type="text" id="new_username" name="new_username" required><br><br>
            <label for="new_password">Yeni Garson Şifre:</label><br>
            <input type="password" id="new_password" name="new_password" required><br><br>
            <input type="submit" name="add_garson" value="Garson Ekle">
        </form>
        <?php if (isset($success)) { ?>
            <p class="message success"><?= $success ?></p>
        <?php } elseif (isset($error)) { ?>
            <p class="message"><?= $error ?></p>
        <?php } ?>

        <h1>Fiyat Güncelle</h1>
        <form method="POST">
            <label for="item_id">Menü Öğesi:</label><br>
            <select id="item_id" name="item_id" required>
                <?php while($item = $items_result->fetch_assoc()) { ?>
                    <option value="<?= $item['id'] ?>"><?= $item['name'] ?> (Mevcut Fiyat: <?= $item['price'] ?>)</option>
                <?php } ?>
            </select><br><br>
            <label for="new_price">Yeni Fiyat:</label><br>
            <input type="number" id="new_price" name="new_price" step="0.01" required><br><br>
            <input type="submit" name="update_price" value="Fiyatı Güncelle">
        </form>
        <?php if (isset($update_success)) { ?>
            <p class="message success"><?= $update_success ?></p>
        <?php } elseif (isset($update_error)) { ?>
            <p class="message"><?= $update_error ?></p>
        <?php } ?>
    </div>
</body>
</html>
