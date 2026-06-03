<?php
require_once '../../includes/db_connect.php';
session_start();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$user = $usersCollection->findOne(['email' => $email]);

if ($user && $user['role'] === 'super_admin' && $user['password'] === $password) {

    $_SESSION['user_id'] = (string)$user['_id'];
    $_SESSION['role'] = 'super_admin';

    header("Location: dashboard.php");
    exit;

} else {
    header("Location: login.php?error=1");
    exit;
}