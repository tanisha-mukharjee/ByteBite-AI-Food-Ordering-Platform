<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';
if (empty($_SESSION['user'])) { header("Location: home.php"); exit; }

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

$usersCollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user']['id'])],
    ['$set' => ['name'=>$name, 'phone'=>$phone, 'address'=>$address]]);

// update session name
$_SESSION['user']['name'] = $name;

header("Location: profile.php");
exit;
