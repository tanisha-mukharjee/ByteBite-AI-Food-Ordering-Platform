<?php
require_once '../../includes/db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurant') {
    header("Location: ../home.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $restaurantId = new MongoDB\BSON\ObjectId($_SESSION['user']['restaurant_id']);

    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = (float)$_POST['price'];

    $imageName = "";

    // Upload image
    if (!empty($_FILES['image']['name'])) {

        $targetDir = "../../assets/uploads/menu/";

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;

        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    }

    // Insert into MongoDB
    $db->menu->insertOne([
        'restaurant_id' => $restaurantId,
        'name' => $name,
        'description' => $description,
        'category' => $category,
        'price' => $price,
        'image' => $imageName, // store only filename
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    header("Location: my_menu.php");
    exit;
}