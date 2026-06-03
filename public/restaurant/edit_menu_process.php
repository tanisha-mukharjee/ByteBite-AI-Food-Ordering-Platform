<?php
require_once '../../includes/db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurant') {
    header("Location: ../home.php");
    exit;
}

$id = new MongoDB\BSON\ObjectId($_POST['id']);

$updateData = [
    'name' => $_POST['name'],
    'description' => $_POST['description'],
    'category' => $_POST['category'],
    'price' => (int)$_POST['price']
];

if (!empty($_FILES['image']['name'])) {

    $imageName = time() . "_" . $_FILES['image']['name'];
    $targetPath = "../../assets/uploads/menu/" . $imageName;

    move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);

    $updateData['image'] = $imageName;
}

$db->menu->updateOne(
    ['_id' => $id],
    ['$set' => $updateData]
);

header("Location: my_menu.php");
exit;