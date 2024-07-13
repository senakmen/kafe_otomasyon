<?php
session_start(); // Oturum başlatır, oturum verilerine erişim sağlar

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




// Masaları boşaltma işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['empty_table_id'])) { // POST isteği ve empty_table_id kontrol edilir
    $tableId = $_POST['empty_table_id']; // Boşaltılacak masa id'si alınır
    
    // Siparişleri silmek için sorgu hazırlanır ve çalıştırılır
    $stmt = $conn->prepare("DELETE FROM orders WHERE table_id = ?");
    $stmt->bind_param("i", $tableId);
    if ($stmt->execute()) {
        // Masayı boşaltmak için durumu güncelleyen sorgu hazırlanır ve çalıştırılır
        $status = 0; // Masa boş
        $stmt = $conn->prepare("UPDATE masalar SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $tableId);
        if ($stmt->execute()) {
            echo "success"; // İşlem başarılı ise success mesajı gösterilir
        } else {
            echo "Masa durumu güncellenemedi: " . $conn->error; // Güncelleme hatası varsa hata mesajı gösterilir
        }
    } else {
        echo "Siparişler silinemedi: " . $conn->error; // Silme hatası varsa hata mesajı gösterilir
    }
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_Accept_id'])) { // POST isteği ve empty_table_id kontrol edilir
    $tableId = $_POST['payment_Accept_id']; // Boşaltılacak masa id'si alınır
    
    // Siparişleri silmek için sorgu hazırlanır ve çalıştırılır
    $stmt = $conn->prepare("DELETE FROM orders WHERE table_id = ?");
    $stmt->bind_param("i", $tableId);
    if ($stmt->execute()) {
        // Masayı boşaltmak için durumu güncelleyen sorgu hazırlanır ve çalıştırılır
        $status = 0; // Masa boş
        $stmt = $conn->prepare("UPDATE masalar SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $tableId);
        if ($stmt->execute()) {
            echo "success"; // İşlem başarılı ise success mesajı gösterilir
        } else {
            echo "Masa durumu güncellenemedi: " . $conn->error; // Güncelleme hatası varsa hata mesajı gösterilir
        }
    } else {
        echo "Siparişler silinemedi: " . $conn->error; // Silme hatası varsa hata mesajı gösterilir
    }
    exit;
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
        header("Location: index.php"); // İşlem başarılı ise ana sayfaya yönlendirilir
        exit();
    } else {
        echo "Masa durumu güncellenemedi: " . $conn->error; // Güncelleme hatası varsa hata mesajı gösterilir
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8"> <!-- Türkçe karakter desteği için UTF-8 karakter seti kullanılır -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Mobil uyumluluk için viewport ayarları -->
    <title>kasa</title> <!-- Sayfa başlığı -->
    <link rel="stylesheet" href="style.css"> <!-- Stil dosyasının bağlanması -->
</head>
<body>
    <div class="tables">
        <h2>Masalar</h2> <!-- Mevcut masaları gösteren başlık -->
        <ul>
            <?php while ($table = $tablesResult->fetch_assoc()) { ?> <!-- Veritabanından çekilen her masa için döngü başlatılır -->
                <li class="<?= $table['status'] == 1 ? 'occupied' : 'empty' ?>"> <!-- Duruma göre sınıf eklenir -->
                    Masa <?= $table['id'] ?> <!-- Masa id'si ve durumu gösterilir -->
                    <?php if ($table['status'] == 0) { ?> <!-- Masa boşsa -->
                        <form method="post" style="display:inline;"> <!-- Masa seçme formu -->
                            <input type="hidden" name="select_table_id" value="<?= $table['id'] ?>"> <!-- Seçilecek masa id'si gizli alan -->
                             <!-- Masa seçme butonu -->
                        </form>
                    <?php } else { ?> <!-- Masa doluysa -->
                        <form method="post" style="display:inline;"> <!-- Masa boşaltma formu -->
                            <input type="hidden" name="empty_table_id" value="<?= $table['id'] ?>"> <!-- Boşaltılacak masa id'si gizli alan -->
                            
                        </form>
                        <a href="masa_siparisleri.php?table_id=<?= $table['id'] ?>">Siparişleri Görüntüle</a> <!-- Siparişleri görüntüleme bağlantısı -->
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </div>
    <script>
        // Sayfayı her 10 saniyede bir yenile
        setInterval(function() {
            location.reload();
        }, 10000); // 10 saniye olarak ayarlandı, istediğiniz süreyi milisaniye cinsinden belirleyebilirsiniz
    </script>

</body>
</html>

<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #128c7e; /* Arka plan rengi */
    margin: 0;
    padding: 0;
}

.tables {
    width: 80%;
    margin: 20px auto;
    padding: 20px;
    background-color: #f8f9fa; /* Tablo arka plan rengi */
    border-radius: 20px; /* Kenar yuvarlama */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Hafif gölgelendirme */
}

h2 {
    text-align: center;
    color: #333; /* Başlık metin rengi */
    margin-top: 20px;
}

ul {
    list-style-type: none;
    padding: 0;
    display: flex; /* Tabloları yatay olarak hizala */
    justify-content: center; /* Tabloları ortala */
    flex-wrap: wrap; /* Eğer tablolar sığmazsa, alt satıra geçmesini sağla */
}

li {
    width: 150px; /* Kutu şeklinde tabloların genişliği ve aralarındaki boşluk */
    height: 150px;
    margin: 20px;
    padding: 20px; /* İçerik boşluğu */
    background-color: #ffffff; /* Tablo arka plan rengi */
    border-radius: 20px; /* Kenar yuvarlama */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Hafif gölgelendirme */
    text-align: center; /* İçerik hizalama */
    transition: transform 0.3s; /* Hareket efekti süresi */
}

li:hover {
    transform: translateY(-5px); /* Fare üzerine geldiğinde hafif yukarı doğru kayma efekti */
}

/* Boş masalar için yeşil arka plan rengi */
li.empty {
    background-color: #28a745; 
    color: #fff; /* Metin rengi beyaz */
}

/* Dolu masalar için kırmızı arka plan rengi */
li.occupied {
    background-color: #dc3545; 
    color: #fff; /* Metin rengi beyaz */
}

button {
    background-color: #007bff; /* Buton arka plan rengi */
    color: #ffffff; /* Buton metin rengi */
    border: none;
    padding: 12px 24px; /* Buton içi boşluk */
    cursor: pointer;
    border-radius: 10px; /* Buton kenar yuvarlama */
    font-size: 16px;
    transition: background-color 0.3s; /* Geçiş efekti süresi */
}

button:hover {
    background-color: #0056b3; /* Fare üzerine gelindiğinde buton rengi */
}

a {
    color: #ffffff; /* Bağlantı metin rengi */
    text-decoration: none;
    font-size: 16px;
    transition: color 0.3s; /* Geçiş efekti süresi */
    position: relative;
    top: 50px;
    display: block; /* Bağlantıyı blok olarak gösterir */
    margin-top: 10px; /* Üstten boşluk ekler */
}

a:hover {
    color: #f8f9fa; /* Fare üzerine gelindiğinde bağlantı rengi */
    text-decoration: underline; /* Fare üzerine gelindiğinde alt çizgi ekler */
}
</style>

   