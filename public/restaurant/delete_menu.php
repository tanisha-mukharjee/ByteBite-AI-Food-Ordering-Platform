<?php
session_start();
require_once __DIR__ . '/../../includes/db_connect.php';
if (!isset($_SESSION['restaurant_id'])) {
    header("Location: login.php");
    exit();
}

$menuCollection = $db->menu;

$id = new MongoDB\BSON\ObjectId($_GET['id']);
$restaurant_id = new MongoDB\BSON\ObjectId($_SESSION['restaurant_id']);

$menuCollection->deleteOne([
    '_id' => $id,
    'restaurant_id' => $restaurant_id
]);

header("Location: menu.php");
exit();