<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Panel - ByteBite</title>
    <link rel="stylesheet" href="/AI_FOOD_ORDER_SYSTEM/assets/css/style.css">
</head>
<body>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2 class="logo">ByteBite</h2>

        <a href="dashboard.php" class="nav-link active">Dashboard</a>
        <a href="my_menu.php" class="nav-link">My Menu</a>
        <a href="add_menu.php" class="nav-link">Add Item</a>
        <a href="orders.php" class="nav-link">Orders</a>

        <a href="../../auth/logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">