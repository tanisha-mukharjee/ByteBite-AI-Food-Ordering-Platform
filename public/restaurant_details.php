<?php
require_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Restaurant ID missing");
}

try {
    $restaurant_id = new MongoDB\BSON\ObjectId($_GET['id']);
} catch (Exception $e) {
    die("Invalid Restaurant ID");
}

$restaurant = $restaurantsCollection->findOne([
    '_id' => $restaurant_id
]);

if (!$restaurant) {
    die("Restaurant not found");
}

$restaurant_id = new MongoDB\BSON\ObjectId($_GET['id']);

$restaurantsCollection = $db->restaurants;
$menuCollection = $db->menu;

$restaurant = $restaurantsCollection->findOne([
    '_id' => $restaurant_id
]);

$menuItems = $menuCollection->find([
    'restaurant_id' => $restaurant_id,
    'is_available' => true
]);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $restaurant['name'] ?> - Menu</title>
    <style>
        body {
            background: linear-gradient(135deg, #141e30, #243b55);
            font-family: Arial;
            color: white;
        }
        .container {
            width: 90%;
            margin: 40px auto;
        }
        .menu-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            margin-right: 15px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><?= $restaurant['name'] ?> Menu</h1>

    <?php foreach ($menuItems as $item): ?>
        <div class="menu-card">
<img src="../assets/uploads/menu/<?php echo htmlspecialchars($item['image']); ?>" width="80">
        <div>
                <h3><?= $item['name'] ?></h3>
                <p>₹<?= $item['price'] ?></p>
                <p><?= $item['category'] ?></p>
            </div>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>