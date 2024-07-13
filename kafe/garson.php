<?php
session_start(); // Oturum başlatır, oturum verilerine erişim sağlar

// Garson giriş kontrolü



// Veritabanı bağlantısı oluşturulur
$conn = new mysqli('localhost', 'root', '', 'cafe_otomasyon');
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error); // Bağlantı hatası varsa hata mesajı gösterilir ve işlem durdurulur
}

// Masaları ve durumlarını çekmek için sorgu çalıştırılır
$tablesResult = $conn->query("SELECT * FROM masalar");
if (!$tablesResult) {
    die("Sorgu hatası: " . $conn->error); // Sorgu hatası varsa hata mesajı gösterilir ve işlem durdurulur
}

// Masa seçme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['select_table_id'])) { // POST isteği ve select_table_id kontrol edilir
    $tableId = $_POST['select_table_id']; // Seçilecek masa id'si alınır
    
    // Masayı dolu olarak güncellemek için sorgu hazırlanır ve çalıştırılır
    $status = 1; // Masa dolu
    $stmt = $conn->prepare("UPDATE masalar SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $status, $tableId);
    if ($stmt->execute()) {
        $_SESSION['selected_table'] = $tableId; // Seçilen masa id'si oturum değişkenine atanır
        header("Location: menu.php"); // İşlem başarılı ise ana sayfaya yönlendirilir
        exit();
    } else {
        echo "Masa durumu güncellenemedi: " . $conn->error; // Güncelleme hatası varsa hata mesajı gösterilir
    }
}

// Sipariş ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_order_table_id'])) {
    $tableId = $_POST['add_order_table_id'];
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    
    // Siparişi veritabanına eklemek için sorgu hazırlanır ve çalıştırılır
    $stmt = $conn->prepare("INSERT INTO siparisler (masa_id, urun, adet) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $tableId, $product, $quantity);
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Sipariş eklenemedi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garson</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #128c7e;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .tables {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            list-style: none;
            padding: 0;
        }

        .table {
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .table:hover {
            transform: translateY(-5px);
        }

        .table h2 {
            margin-bottom: 10px;
            font-size: 24px;
            color: #333;
        }

        .table p {
            font-size: 16px;
            color: #fff;
        }

        .table button {
            background-color: white;
            color: black;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
            font-color
        }

        .table button:hover {
            background-color: white;
            color: blue;
        }

        .table.empty {
            background-color: #4CAF50; /* Yeşil */
        }

        .table.occupied {
            background-color: #dc3545; /* Kırmızı */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Masalar</h1>
        <ul class="tables">
            <?php while ($table = $tablesResult->fetch_assoc()) { ?>
                <li class="table <?= $table['status'] == 1 ? 'occupied' : 'empty' ?>">
                    <h2>Masa <?= $table['id'] ?></h2>
                    <form method="post">
                        <input type="hidden" name="select_table_id" value="<?= $table['id'] ?>">
                        <button type="submit">Masa Seç</button>
                    </form>
                </li>
            <?php } ?>
        </ul>
    </div>
</body>
</html>
