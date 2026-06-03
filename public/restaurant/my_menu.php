<?php
require_once '../../includes/db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurant') {
    header("Location: ../home.php");
    exit;
}

$restaurantId = new MongoDB\BSON\ObjectId($_SESSION['user']['restaurant_id']);

$menuItems = $db->menu->find([
    'restaurant_id' => $restaurantId
]);

require_once '../header.php';
?>

<div class="bb-container" style="margin-top:40px;">

    <h2>My Menu Items</h2>

    <div class="menu-grid">

        <?php foreach ($menuItems as $item): ?>
            <div class="menu-card">

    <!-- IMAGE -->
    <div class="menu-img-wrapper">
        <?php if (!empty($item['image'])): ?>
            <img src="/AI_FOOD_ORDER_SYSTEM/assets/uploads/menu/<?= htmlspecialchars($item['image']) ?>"
                 class="menu-img">
        <?php else: ?>
            <div class="menu-img placeholder"></div>
        <?php endif; ?>
    </div>

    <!-- INFO -->
    <div class="menu-info">
        <h4><?= htmlspecialchars($item['name']) ?></h4>
        <p><?= htmlspecialchars($item['category']) ?></p>
        <strong>₹<?= $item['price'] ?></strong>

        <div style="margin-top:8px;">
            <?php if (!isset($item['is_available']) || $item['is_available'] === true): ?>
                <span class="status-badge active">Available</span>
            <?php else: ?>
                <span class="status-badge inactive">Out of Stock</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- ACTIONS -->
    <div class="menu-actions">
        <a href="toggle_menu.php?id=<?= $item['_id'] ?>" class="btn-small">
            <?php if (!isset($item['is_available']) || $item['is_available'] === true): ?>
                Mark as Out of Stock
            <?php else: ?>
                Mark as Available
            <?php endif; ?>
        </a>
        <a href="edit_menu.php?id=<?= $item['_id'] ?>" class="btn-small-outline">
    Edit
</a>
        <a href="delete_menu.php?id=<?= $item['_id'] ?>" class="btn-small-outline">
            Delete
        </a>
        
    </div>

</div>
        <?php endforeach; ?>

    </div>

    <div style="margin-top:25px;">
        <a class="btn-pill-outline" href="dashboard.php">
            Back to Dashboard
        </a>
    </div>

</div>

<?php require_once '../footer.php'; ?>