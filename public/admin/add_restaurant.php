<?php
require_once '../../includes/db_connect.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1️⃣ Insert into restaurants collection
    $restaurantInsert = $restaurantsCollection->insertOne([
        'name' => $name,
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s')
    ]);

    $restaurantId = $restaurantInsert->getInsertedId();

    // 2️⃣ Insert restaurant login into users collection
    $usersCollection->insertOne([
        'name' => $name . " Owner",
        'email' => $email,
        'password' => $password,
        'role' => 'restaurant',
        'restaurant_id' => $restaurantId
    ]);

    echo "Restaurant Created Successfully!";
}
?>

<h2>Add Restaurant</h2>

<form method="POST">
    <input type="text" name="name" placeholder="Restaurant Name" required><br><br>
    <input type="email" name="email" placeholder="Restaurant Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Create Restaurant</button>
</form>

<br>
<a href="dashboard.php">Back to Dashboard</a>