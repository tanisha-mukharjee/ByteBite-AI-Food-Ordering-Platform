<?php
require_once '../../includes/db_connect.php';
require_once 'layout.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurant') {
    header("Location: ../home.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: my_menu.php");
    exit;
}

$id = new MongoDB\BSON\ObjectId($_GET['id']);

$item = $db->menu->findOne(['_id' => $id]);

if ($item) {
    $currentStatus = $item['is_available'] ?? true;

    $db->menu->updateOne(
        ['_id' => $id],
        ['$set' => ['is_available' => !$currentStatus]]
    );
}

header("Location: my_menu.php");
exit;