<?php
session_start();
require_once __DIR__ . '/../../includes/db_connect.php';
if (!isset($_SESSION['restaurant_id'])) {
    header("Location: login.php");
    exit();
}

$restaurant_id = new MongoDB\BSON\ObjectId($_SESSION['restaurant_id']);
$menuCollection = $db->menu;

$menuItems = $menuCollection->find([
    'restaurant_id' => $restaurant_id
]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Menu - ByteBite</title>
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
        .glass-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #ff5a5f;
            padding: 12px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
        }
        a {
            color: #ff5a5f;
            text-decoration: none;
            font-weight: bold;
        }
        .add-btn {
            display: inline-block;
            margin-bottom: 20px;
            background: #ff5a5f;
            padding: 10px 15px;
            border-radius: 8px;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>My Menu</h2>
    <a class="add-btn" href="add_menu.php">+ Add New Item</a>

    <div class="glass-card">
        <table>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach ($menuItems as $item): ?>
            <tr>
                <td>
                    <?php if (!empty($item['image'])): ?>
                        <img src="../assets/uploads/menu/<?= $item['image'] ?>">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td><?= $item['name'] ?></td>
                <td><?= $item['category'] ?></td>
                <td>₹<?= $item['price'] ?></td>
                <td><?= $item['is_available'] ? "Available" : "Out of Stock" ?></td>
                <td>
                    <a href="edit_menu.php?id=<?= $item['_id'] ?>">Edit</a> |
                    <a href="delete_menu.php?id=<?= $item['_id'] ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
    </div>
</div>

</body>
</html>