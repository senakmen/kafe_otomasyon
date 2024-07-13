<?php
session_start(); // Oturum başlatır, oturum verilerine erişim sağlar

// Veritabanı bağlantısı oluşturulur
$conn = new mysqli('localhost', 'root', '', 'cafe_otomasyon');
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error); // Bağlantı hatası varsa hata mesajı gösterilir ve işlem durdurulur
}

// GET isteği ile gelen table_id parametresi kontrol edilir
if (isset($_GET['table_id'])) {
    $tableId = $_GET['table_id']; // table_id değeri alınır
    
    // Belirtilen table_id için siparişler ve ilgili menü öğeleri getirilir
    $ordersResult = $conn->query("SELECT o.*, m.name FROM orders o JOIN menu_items m ON o.item_id = m.id WHERE table_id = $tableId");
    if (!$ordersResult) {
        die("Sorgu hatası: " . $conn->error); // Sorgu hatası varsa hata mesajı gösterilir ve işlem durdurulur
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masa Siparişleri</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Masa <?= htmlspecialchars($tableId) ?> Siparişleri</h1>
        <ul class="orders-list">
            <?php 
            $totalPrice = 0; // Toplam fiyatı saklamak için bir değişken oluşturuyoruz
            while ($order = $ordersResult->fetch_assoc()) { 
                $totalPrice += $order['total_price']; // Siparişlerin toplam fiyatını topluyoruz
            ?>
                <li><?= htmlspecialchars($order['name']) ?> x<?= htmlspecialchars($order['quantity']) ?> - <?= htmlspecialchars($order['total_price']) ?>₺</li>
            <?php } ?>
        </ul>
        <?php
        $kdvOrani = 0.10; // KDV oranını belirliyoruz (%10)
        $kdv = $totalPrice * $kdvOrani; // Toplam fiyatın %1'i KDV
        $toplamFiyatKDVli = $totalPrice + $kdv; // KDV'li toplam fiyat
        ?>
        <p>Toplam: <?= number_format($totalPrice, 2) ?>₺</p>
        <p>KDV (%10): <?= number_format($kdv, 2) ?>₺</p>
        <p>Toplam (KDV Dahil): <?= number_format($toplamFiyatKDVli, 2) ?>₺</p>
        <div class="buttons">
            <a href="kasa.php" class="btn btn-secondary">Masalar</a>
            <button onclick="paymentAccepted(<?= $tableId ?>)" class="btn btn-primary">Ödeme</button>
            
        </div>
    </div>

    <script>
        
        function paymentAccepted(tableId) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "kasa.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert('Ödeme Tamamlandı.');
                        window.location.href = 'kasa.php';
                    } else {
                        alert('Ödeme alınırken hata oldu.');
                    }
                }
            };
            xhr.send("payment_Accept_id=" + tableId);
        }
    </script>
</body>
</html>
<style>
/* Fontlar */
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #128c7e;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1 {
    margin-top: 0;
    font-size: 28px;
    text-align: center;
    color: #333;
}

.orders-list {
    list-style-type: none;
    padding: 0;
}

.orders-list li {
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
}

.buttons {
    margin-top: 20px;
    text-align: center;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    margin: 0 10px;
    font-size: 16px;
    text-decoration: none;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #545b62;
}
