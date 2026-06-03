<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

header('Content-Type: application/json');

$menuCollection = $db->menu;

$action = $_POST['action'] ?? '';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        'restaurant_id' => null,
        'items' => []
    ];
}

/* ================= ADD ITEM ================= */
if ($action === 'add') {

    $itemId = $_POST['item_id'] ?? '';
    $restaurantId = $_POST['restaurant_id'] ?? 'default';

    if (!$itemId) {
        echo json_encode(['status' => 'error']);
        exit;
    }

    // Try DB
    $item = null;

    try {
        $item = $menuCollection->findOne([
            '_id' => new MongoDB\BSON\ObjectId($itemId)
        ]);
    } catch (Exception $e) {}

    // Fallback if DB fails
    if (!$item) {
        $item = [
            'name'  => $_POST['item_name'] ?? 'Item',
            'price' => $_POST['price'] ?? 100
        ];
    }

    // Save in session
    if (isset($_SESSION['cart']['items'][$itemId])) {
        $_SESSION['cart']['items'][$itemId]['qty']++;
    } else {
        $_SESSION['cart']['items'][$itemId] = [
            'name'  => $item['name'],
            'price' => $item['price'],
            'qty'   => 1
        ];
    }

    echo json_encode([
        'status' => 'success',
        'count'  => array_sum(array_column($_SESSION['cart']['items'], 'qty'))
    ]);
    exit;
}

/* ================= GET ================= */
if ($action === 'get') {

    $items = [];

    foreach ($_SESSION['cart']['items'] as $id => $item) {
        $items[] = [
            'id' => $id,
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['qty']
        ];
    }

    echo json_encode([
        'status' => 'success',
        'items' => $items
    ]);
    exit;
}

/* ================= COUNT ================= */
if ($action === 'count') {

    $count = array_sum(array_column($_SESSION['cart']['items'], 'qty'));

    echo json_encode(['count' => $count]);
    exit;
}

echo json_encode(['status' => 'invalid']);