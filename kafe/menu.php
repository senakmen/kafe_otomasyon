<?php
session_start();
// Veritabanı bağlantısı
$conn = new mysqli('localhost', 'root', '', 'cafe_otomasyon');
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Menü öğelerini veritabanından çek
$menuItems = $conn->query("SELECT * FROM menu_items");

// Resim yolları
$imagePaths = [
    'Americano' => "resim/americano.jpeg",
    'Latte' => "resim/latte1.jpeg",
    'Burger' => "resim/Burger.jpeg  ",
    'Wrap' => "resim/wrap1.jpg",
    'Çay' => "images/cay-kac-kalori.jpg",
    'Türk Kahvesi' => "images/trkahve.jpg",
    'Kahvaltı Tabağı' => "images/kahvalti_tabagi.jpg",
    'Serpme Kahvaltı' => "images/resized_e343b-01e53a06serpmee.jpg",
    'Fettucini Alfredo' => "images/c71a42381ff9eaaf908003f953cc30171650.jpg",
    'Kola' => "images/kola.jpg",
    'Ice Tea' => "images/icetea.jpg",
    'Gazoz' => "images/gazoz.jpg",
    'Filtre Kahve' => "resim/filtre.jpg",
    'Sezar Salata' => "images/sezarsalata.jpg",
    'Pizza' => "resim/pizza.jpeg",
    'Karışık Tost' => "resim/Tost.jpeg",

];

$defaultImagePath = "images/default_image.jpg";

// Sipariş ekleme fonksiyonu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'])) {
    if (!isset($_SESSION['selected_table'])) {
        echo json_encode(['error' => 'Masa seçilmedi.']);
        exit;
    }

    // Ürün ID'sini ve masa ID'sini al
    $itemId = $_POST['item_id'];
    $tableId = $_SESSION['selected_table'];
    $quantity = 1; // Varsayılan miktar

    // Ürünü veritabanından çek
    $result = $conn->query("SELECT * FROM menu_items WHERE id = $itemId");
    $item = $result->fetch_assoc();
    $totalPrice = $item['price'] * $quantity;

    // Siparişi oturumda sakla
    $_SESSION['order'][] = [
        'item_id' => $itemId,
        'quantity' => $quantity,
        'total_price' => $totalPrice,
        'name' => $item['name'],
        'price' => $item['price'],
        'table_id' => $tableId
    ];

    // Sipariş listesini JSON olarak geri döndür
    echo json_encode($_SESSION['order']);
    exit;
}

// Siparişten öğe çıkarma fonksiyonu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_item_id'])) {
    $removeItemId = $_POST['remove_item_id'];
    foreach ($_SESSION['order'] as $key => $order) {
        if ($order['item_id'] == $removeItemId) {
            unset($_SESSION['order'][$key]);
            $_SESSION['order'] = array_values($_SESSION['order']); // Dizi anahtarlarını yeniden sıralayın
            break;
        }
    }
    echo json_encode($_SESSION['order']);
    exit;
}

// Siparişi tamamlama fonksiyonu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    foreach ($_SESSION['order'] as $order) {
        // Siparişleri veritabanına ekle
        $stmt = $conn->prepare("INSERT INTO orders (item_id, quantity, total_price, table_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iidi", $order['item_id'], $order['quantity'], $order['total_price'], $order['table_id']);
        $stmt->execute();
    }

    // Oturumu temizle
    unset($_SESSION['order']);
    echo json_encode(['success' => true]);
    exit;
}
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #128c7e;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    height: 100vh;
}

.container {
    display: flex;
    width: 100%;
    max-width: 1200px;
}

.menu {
    width: 80%;
    background-color: #fff;
    padding: 20px;
    box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    border-right: 1px solid #ddd;
    height: 100vh;
}

.order {
    width: 50%;
    background-color: #fff;
    padding: 20px;
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    height: 100vh;
}

.category {
    margin-bottom: 20px;
}

.category h2 {
    font-size: 18px;
    margin-bottom: 10px;
}

.category-items {
    list-style-type: none;
    padding: 0;
}

.menu-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #fff;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.menu-item img {
    max-width: 50px;
    height: auto;
    border-radius: 8px;
}

.menu-item-details {
    flex: 1;
    margin-left: 10px;
}

.menu-item h3 {
    font-size: 16px;
    margin: 0;
}

.menu-item p {
    font-size: 14px;
    color: #888;
    margin: 5px 0;
}

.menu-item button {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.menu-item button:hover {
    background-color: #0056b3;
}

.order h2 {
    font-size: 18px;
    margin-bottom: 10px;
}

#order-list {
    list-style-type: none;
    padding: 0;
}

#order-list li {
    margin-bottom: 10px;
    font-size: 14px;
}

#order-list li button {
    background-color: #dc3545;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 3px 7px;
    cursor: pointer;
    transition: background-color 0.3s;
}

#order-list li button:hover {
    background-color: #c82333;
}

.order button {
    width: 100%;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 8px 0;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 10px;
}

.order button:hover {
    background-color: #218838;
}

</style>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kafe Otomasyon Sistemi</title>
</head>
<body>
    <div class="menu">
        <?php
        // Menü öğelerini kategorilere göre gruplandır
        $categories = [];
        while ($row = $menuItems->fetch_assoc()) {
            $category = $row['category'];
            if (!isset($categories[$category])) {
                $categories[$category] = [];
            }
            $categories[$category][] = $row;
        }

        // Her kategoriyi ve içindeki öğeleri görüntüle
        foreach ($categories as $category => $items) {
            echo "<div class='category'><h2>$category</h2><div class='category-items'>";
            foreach ($items as $item) {
                $imagePath = isset($imagePaths[$item['name']]) ? $imagePaths[$item['name']] : $defaultImagePath;
                ?>
                <div class="menu-item">
                    <img src="<?= $imagePath ?>" alt="<?= $item['name'] ?>">
                    <h3><?= $item['name'] ?></h3>
                    <p><?= $item['price'] ?>₺</p>
                    <button onclick="addToOrder(<?= $item['id'] ?>)">Siparişe Ekle</button>
                </div>
                <?php
            }
            echo "</div></div>";
        }
        ?>
    </div>

    <div class="order">
        <h2>Mevcut Sipariş</h2>
        <ul id="order-list">
            <?php
            // Oturumdaki mevcut siparişleri görüntüle
            if (isset($_SESSION['order'])) {
                foreach ($_SESSION['order'] as $order) {
                    echo "<li>{$order['name']} x{$order['quantity']} - {$order['total_price']}₺ <button onclick='removeFromOrder({$order['item_id']})'>Sil</button></li>";
                }
            }
            ?>
        </ul>
        <button onclick="checkout()">Siparişi Tamamla</button>
    </div>

    <form id="checkout-form" action="" method="POST">
        <input type="hidden" name="checkout" value="true">
    </form>

    <script>
        // Siparişe öğe ekleme fonksiyonu
        function addToOrder(itemId) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'item_id=' + itemId
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                // Sipariş listesini güncelle
                const orderList = document.getElementById('order-list');
                orderList.innerHTML = '';
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.innerHTML = `${item.name} x${item.quantity} - ${item.total_price}₺ <button onclick='removeFromOrder(${item.item_id})'>Sil</button>`;
                    orderList.appendChild(li);
                });
            });
        }

        // Siparişten öğe çıkarma fonksiyonu
        function removeFromOrder(itemId) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'remove_item_id=' + itemId
            })
            .then(response => response.json())
            .then(data => {
                // Sipariş listesini güncelle
                const orderList = document.getElementById('order-list');
                orderList.innerHTML = '';
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.innerHTML = `${item.name} x${item.quantity} - ${item.total_price}₺ <button onclick='removeFromOrder(${item.item_id})'>Sil</button>`;
                    orderList.appendChild(li);
                });
            });
        }

        // Siparişi tamamlama fonksiyonu
        function checkout() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'checkout=true'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Siparişiniz başarıyla kaydedildi!');
                    window.location.href = "garson.php"; // garson.php sayfasına yönlendir
                }
            });
        }
    </script>
</body>
</html>
