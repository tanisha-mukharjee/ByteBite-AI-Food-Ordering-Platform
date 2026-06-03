<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

$name = trim($_POST['name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
  $_SESSION['register_error']="Fill all fields";
  header("Location: /AI_Food_Order_System/public/register.php");
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['register_error']="Invalid email";
  header("Location: /AI_Food_Order_System/public/register.php");
  exit;
}

// check existing
$existing = $usersCollection->findOne(['email'=>$email]);
if ($existing) {
  $_SESSION['register_error']="Email already used";
  header("Location: /AI_Food_Order_System/public/register.php");
  exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$ins = $usersCollection->insertOne([
  'name'=>$name,
  'email'=>$email,
  'password'=>$hash,
  'created_at'=>new MongoDB\BSON\UTCDateTime()
]);

if ($ins->getInsertedId()) {
  $_SESSION['user'] = [
    'id'=>(string)$ins->getInsertedId(),
    'name'=>$name,
    'email'=>$email
  ];
  header("Location: /AI_Food_Order_System/public/home.php");
  exit;
}

$_SESSION['register_error']="Register failed";
header("Location: /AI_Food_Order_System/public/register.php");
exit;
