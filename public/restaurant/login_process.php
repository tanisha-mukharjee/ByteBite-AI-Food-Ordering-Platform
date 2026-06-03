<?php
require_once '../../includes/db_connect.php';
session_start();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$user = $usersCollection->findOne(['email' => $email]);

if ($user && $user['role'] === 'restaurant' && $user['password'] === $password) {

    $_SESSION['user'] = [
        'id' => (string)$user['_id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
        'restaurant_id' => (string)$user['restaurant_id']
    ];

    header("Location: dashboard.php");
    exit;

} else {
    header("Location: login.php?error=1");
    exit;
}