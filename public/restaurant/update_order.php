<?php
require_once '../../includes/db_connect.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurant') {
    header("Location: ../home.php");
    exit;
}

$orderId = $_GET['id'] ?? '';
$status = $_GET['status'] ?? '';

if (!$orderId || !$status) {
    header("Location: dashboard.php");
    exit;
}

$db->orders->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($orderId)],
    ['$set' => ['status' => $status]]
);

header("Location: dashboard.php");
exit;