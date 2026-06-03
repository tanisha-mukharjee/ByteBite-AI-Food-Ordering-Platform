<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

$ordersCollection = $db->orders;

/* ===============================
   VALIDATION
================================ */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
    exit;
}

if (empty($_SESSION['user'])) {
    header("Location: home.php?login=1");
    exit;
}

if (empty($_SESSION['cart']['items'])) {
    header("Location: home.php");
    exit;
}

$address = trim($_POST['address'] ?? '');
$payment = $_POST['payment'] ?? 'cod';

if (!$address) {
    $_SESSION['order_error'] = "Address required.";
    header("Location: checkout.php");
    exit;
}

/* ===============================
   GET CART FROM SESSION
================================ */

$cartItems = $_SESSION['cart']['items'];

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['qty'];
}

/* ===============================
   CREATE ORDER
================================ */

$user = $_SESSION['user'];

/* ===============================
   RESTAURANT VALIDATION
================================ */

$restaurantId = $_SESSION['cart']['restaurant_id'] ?? null;

if (!$restaurantId || $restaurantId === 'undefined') {
    $_SESSION['order_error'] = "Invalid restaurant. Please add items again.";
    header("Location: checkout.php");
    exit;
}

try {
    $restaurantObjectId = new MongoDB\BSON\ObjectId($restaurantId);
} catch (Exception $e) {
    $_SESSION['order_error'] = "Restaurant ID error.";
    header("Location: checkout.php");
    exit;
}

/* ===============================
   CREATE ORDER
================================ */

$order = [
    'user_id'       => $user['id'] ?? null,
    'user_name'     => $user['name'] ?? '',
    'user_email'    => $user['email'] ?? '',
    'restaurant_id' => $restaurantObjectId,
    'items'         => $cartItems,
    'address'       => $address,
    'payment_method'=> $payment,
    'total'         => $total,
    'status'        => 'Pending',
    'created_at'    => new MongoDB\BSON\UTCDateTime()
];
$res = $ordersCollection->insertOne($order);

/* ===============================
   SUCCESS
================================ */

if ($res->getInsertedId()) {

    // 🔥 CLEAR CART AFTER ORDER
    unset($_SESSION['cart']);

    header("Location: order_success.php?order_id=" . (string)$res->getInsertedId());
    exit;

} else {

    $_SESSION['order_error'] = "Order failed.";
    header("Location: checkout.php");
    exit;
}