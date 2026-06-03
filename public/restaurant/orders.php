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

$orders = $db->orders->find(
    ['restaurant_id' => $restaurantId],
    ['sort' => ['created_at' => -1]]
);

require_once 'layout.php';
?>

<h2 style="margin-bottom:30px;">Orders</h2>

<div class="orders-container">

<?php
$hasOrders = false;
foreach ($orders as $order):
$hasOrders = true;
?>

<div class="order-card">

    <div class="order-top">
        <div>
            <strong>Order #<?= substr((string)$order['_id'], -6) ?></strong>
            <p class="order-date">
                <?= isset($order['created_at'])
                    ? $order['created_at']->toDateTime()->format('d M Y, h:i A')
                    : '-' ?>
            </p>
        </div>

        <div>
            <strong>₹<?= $order['total'] ?></strong>
        </div>
    </div>

    <div class="order-items">
        <?php foreach ($order['items'] as $item): ?>
            <div class="order-item-row">
                <?= $item['name'] ?> × <?= $item['quantity'] ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="order-bottom">

        <?php
        $status = $order['status'];
        $badgeClass = '';

        if ($status == 'Pending') $badgeClass = 'badge-pending';
        if ($status == 'Preparing') $badgeClass = 'badge-preparing';
        if ($status == 'Completed') $badgeClass = 'badge-completed';
        if ($status == 'Cancelled') $badgeClass = 'badge-cancelled';
        ?>

        <span class="status-badge <?= $badgeClass ?>">
            <?= $status ?>
        </span>

        <div class="order-actions">

            <?php if ($status == 'Pending'): ?>
                <a href="update_order.php?id=<?= $order['_id'] ?>&status=Preparing"
                   class="btn-accept">Accept</a>

                <a href="update_order.php?id=<?= $order['_id'] ?>&status=Cancelled"
                   class="btn-reject">Reject</a>
            <?php endif; ?>

            <?php if ($status == 'Preparing'): ?>
                <a href="update_order.php?id=<?= $order['_id'] ?>&status=Completed"
                   class="btn-complete">Mark Completed</a>
            <?php endif; ?>

        </div>

    </div>

</div>

<?php endforeach; ?>

<?php if (!$hasOrders): ?>
    <div class="no-orders">
        🟠 No orders yet.
    </div>
<?php endif; ?>

</div>

</div>
</body>
</html>