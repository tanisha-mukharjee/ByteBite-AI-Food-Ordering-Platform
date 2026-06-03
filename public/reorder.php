<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

if (empty($_POST['order_id'])) {
  header("Location: order_history.php");
  exit;
}

$orderId = $_POST['order_id'];

$order = $ordersCollection->findOne([
  '_id' => new MongoDB\BSON\ObjectId($orderId)
]);

if (!$order || empty($order['items'])) {
  header("Location: order_history.php");
  exit;
}

// Put items back into cart using JS
echo "<script>
  localStorage.setItem('bb_cart', '".json_encode($order['items'])."');
  window.location.href = '/AI_Food_Order_System/public/menu.php';
</script>";
exit;
