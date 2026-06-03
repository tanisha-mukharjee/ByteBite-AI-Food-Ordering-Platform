<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
if (!$email || !$password) { $_SESSION['login_error']="Enter credentials"; header("Location: /AI_Food_Order_System/public/home.php?login=1"); exit; }

$userDoc = $usersCollection->findOne(['email'=>$email]);
if (!$userDoc || !password_verify($password, $userDoc['password'] ?? '')) {
  $_SESSION['login_error']="Invalid email or password";
  header("Location: /AI_Food_Order_System/public/home.php?login=1"); exit;
}

session_regenerate_id(true);
$_SESSION['user'] = ['id' => (string)$userDoc['_id'], 'name'=>$userDoc['name'], 'email'=>$userDoc['email']];
header("Location: /AI_Food_Order_System/public/home.php");
exit;
