<?php
require_once '../../includes/db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurant') {
    header("Location: ../home.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: my_menu.php");
    exit;
}

$id = new MongoDB\BSON\ObjectId($_GET['id']);

$item = $db->menu->findOne(['_id' => $id]);

if (!$item) {
    header("Location: my_menu.php");
    exit;
}

require_once '../header.php';
?>

<div class="bb-container" style="margin-top:50px; display:flex; justify-content:center;">

    <div class="edit-card">

        <h2>Edit Menu Item</h2>

        <form action="edit_menu_process.php" method="POST" enctype="multipart/form-data" class="edit-form">

            <input type="hidden" name="id" value="<?= $item['_id'] ?>">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name"
                       value="<?= htmlspecialchars($item['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category"
                       value="<?= htmlspecialchars($item['category']) ?>" required>
            </div>

            <div class="form-group">
                <label>Price (₹)</label>
                <input type="number" name="price"
                       value="<?= $item['price'] ?>" required>
            </div>

            <div class="form-group">
                <label>Change Image (optional)</label>
                <input type="file" name="image">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-pill">
                    Update Item
                </button>

                <a href="my_menu.php" class="btn-pill-outline">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

<?php require_once '../footer.php'; ?>