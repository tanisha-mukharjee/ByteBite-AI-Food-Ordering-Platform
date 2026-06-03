<?php
require_once '../../includes/db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurant') {
    header("Location: ../home.php");
    exit;
}

require_once '../header.php'; // Use main layout
?>

<div class="bb-container" style="margin-top:40px; max-width:600px;">

    <h2>Add Menu Item</h2>

    <div class="form-card">

    <h2 style="margin-bottom:25px;">Add Menu Item</h2>

    <form method="POST" action="add_menu_process.php" enctype="multipart/form-data" class="vertical-form">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category">
                <option>Starter</option>
                <option>Main Course</option>
                <option>Dessert</option>
                <option>Beverage</option>
            </select>
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="number" name="price" required>
        </div>

        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image">
        </div>

        <button type="submit" class="btn-pill" style="margin-top:15px;">
            Add Item
        </button>

    </form>

    <div style="margin-top:20px;">
        <a class="btn-pill-outline" href="dashboard.php">
            Back to Dashboard
        </a>
    </div>

</div>
</div>

<?php require_once '../footer.php'; ?>