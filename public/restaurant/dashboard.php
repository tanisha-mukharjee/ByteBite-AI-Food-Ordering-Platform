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


/* ===========================
   BASIC COUNTS
=========================== */

$menuCount = $db->menu->countDocuments([
    'restaurant_id' => $restaurantId
]);

$orderCount = $db->orders->countDocuments([
    'restaurant_id' => $restaurantId
]);

/* ===========================
   TOTAL REVENUE (Completed Orders)
=========================== */

$completedOrders = $db->orders->find([
    'restaurant_id' => $restaurantId,
    'status' => 'Completed'
]);

$totalRevenue = 0;

foreach ($completedOrders as $order) {
    $totalRevenue += $order['total'];
}

/* ===========================
   BEST SELLING ITEM
=========================== */

$bestSelling = $db->orders->aggregate([
    ['$match' => ['restaurant_id' => $restaurantId]],
    ['$unwind' => '$items'],
    ['$group' => [
        '_id' => '$items.name',
        'totalSold' => ['$sum' => '$items.quantity']
    ]],
    ['$sort' => ['totalSold' => -1]],
    ['$limit' => 1]
])->toArray();

/* ===========================
   RECENT ORDERS
=========================== */

$recentOrders = $db->orders->find(
    ['restaurant_id' => $restaurantId],
    ['sort' => ['created_at' => -1], 'limit' => 5]
);

/* ===========================
   SIDEBAR LAYOUT
=========================== */

require_once 'layout.php';
?>
<?php
$pendingCount = $db->orders->countDocuments([
    'restaurant_id' => $restaurantId,
    'status' => 'Pending'
]);
?>

<?php if ($pendingCount > 0): ?>
    <div class="new-orders-alert">
        🟠 <?= $pendingCount ?> New Order(s) Pending
    </div>
<?php endif; ?>

<h2>Restaurant Dashboard</h2>

<div class="stats-grid">

    <div class="stat-card">
        <h4>Total Menu Items</h4>
        <p><?= $menuCount ?></p>
    </div>

    <div class="stat-card">
        <h4>Total Orders</h4>
        <p><?= $orderCount ?></p>
    </div>

    <div class="stat-card">
        <h4>Total Revenue</h4>
        <p>₹<?= $totalRevenue ?></p>
    </div>

    <?php if (!empty($bestSelling)): ?>
        <div class="stat-card">
            <h4>Best Selling Item</h4>
            <p><?= htmlspecialchars($bestSelling[0]['_id']) ?></p>
        </div>
    <?php endif; ?>

</div>

<h3 style="margin-top:40px;">Recent Orders</h3>

<table class="orders-table">
    <tr>
        <th>Order ID</th>
        <th>Total</th>
        <th>Status</th>
        <th>Date</th>
    </tr>

<?php
$hasOrders = false;
foreach ($recentOrders as $order):
    $hasOrders = true;
?>
    <tr>
        <td><?= substr((string)$order['_id'], -6) ?></td>
        <td>₹<?= $order['total'] ?></td>

        <td>
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
        </td>

        <td>
            <?= isset($order['created_at'])
                ? $order['created_at']->toDateTime()->format('d M Y')
                : '-' ?>
        </td>
    </tr>

<?php endforeach; ?>

<?php if (!$hasOrders): ?>
    <tr>
        <td colspan="4" style="text-align:center; padding:20px; color:#6b7280;">
            No orders yet 🚀
        </td>
    </tr>
<?php endif; ?>

</table>
<!-- REVENUE CHART -->
<h3 style="margin-top:50px;">Revenue Overview</h3>

<canvas id="revenueChart" height="100"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const revenueValue = <?= $totalRevenue ?>;

const ctx = document.getElementById('revenueChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Total Revenue'],
        datasets: [{
            label: 'Revenue (₹)',
            data: [revenueValue],
            backgroundColor: '#ff7a00'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                suggestedMax: revenueValue > 0 ? undefined : 100
            }
        }
    }
});
</script>
<?php if ($orderCount == 0): ?>
    <div style="margin-top:30px; padding:20px; background:#fff4ec; border-radius:12px;">
        🟠 Waiting for your first order...
    </div>
<?php endif; ?>
    </div> <!-- main-content -->
</div> <!-- admin-layout -->

</body>
</html>