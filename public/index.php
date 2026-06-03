<?php
require_once __DIR__ . '/../includes/db_connect.php';

$restaurantsCollection = $db->restaurants;
$restaurants = $restaurantsCollection->find();
?>

<!DOCTYPE html>
<html>
<head>
    <title>ByteBite - Restaurants</title>
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
        .restaurant-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        .restaurant-card:hover {
            transform: scale(1.02);
        }
        a {
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🍔 ByteBite Restaurants</h1>

    <?php foreach ($restaurants as $restaurant): ?>
        <a href="restaurant_details.php?id=<?= $restaurant['_id'] ?>">
            <div class="restaurant-card">
                <h2><?= $restaurant['name'] ?></h2>
                <p><?= $restaurant['location'] ?? 'Mumbai' ?></p>
            </div>
        </a>
    <?php endforeach; ?>

</div>

</body>
</html>