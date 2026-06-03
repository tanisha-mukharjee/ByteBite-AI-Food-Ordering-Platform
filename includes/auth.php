<?php
session_start();

function requireRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: /AI_FOOD_ORDER_SYSTEM/public/home.php");
        exit;
    }
}
?>