<?php
require_once '../../includes/auth.php';
require_once '../../includes/db_connect.php';

requireRole('super_admin');

$usersCount = $db->users->countDocuments();
$restaurantCount = $db->restaurants->countDocuments();
$orderCount = $db->orders->countDocuments();

$orders = $db->orders->find()->toArray();

$totalRevenue = 0;
$todayRevenue = 0;
$restaurantSales = [];

$todayStart = new MongoDB\BSON\UTCDateTime(
    strtotime("today") * 1000
);

foreach ($orders as $order) {

    $totalRevenue += $order['total'] ?? 0;

    if (isset($order['created_at']) && $order['created_at'] >= $todayStart) {
        $todayRevenue += $order['total'] ?? 0;
    }

    $rid = (string)($order['restaurant_id'] ?? '');

    if ($rid) {
        if (!isset($restaurantSales[$rid])) {
            $restaurantSales[$rid] = 0;
        }
        $restaurantSales[$rid] += $order['total'] ?? 0;
    }
}

arsort($restaurantSales);
$topRestaurantId = array_key_first($restaurantSales);

$topRestaurant = null;
if ($topRestaurantId) {
    $topRestaurant = $db->restaurants->findOne([
        '_id' => new MongoDB\BSON\ObjectId($topRestaurantId)
    ]);
}
?>

<link rel="stylesheet" href="../../assets/css/style.css">

<div class="bb-container" style="margin-top:40px;">

<h2>Super Admin Dashboard 👑</h2>

<div class="dashboard-grid">

<div class="stat-card">
<h3>Total Users</h3>
<p><?= $usersCount ?></p>
</div>

<div class="stat-card">
<h3>Total Restaurants</h3>
<p><?= $restaurantCount ?></p>
</div>

<div class="stat-card">
<h3>Total Orders</h3>
<p><?= $orderCount ?></p>
</div>

<div class="stat-card">
<h3>Total Revenue</h3>
<p>₹<?= number_format($totalRevenue) ?></p>
</div>

<div class="stat-card">
<h3>Today's Revenue</h3>
<p>₹<?= number_format($todayRevenue) ?></p>
</div>

<div class="stat-card">
<h3>Top Restaurant</h3>
<p><?= $topRestaurant['name'] ?? 'N/A' ?></p>
</div>

</div>

<hr style="margin:40px 0;">

<h3>Recent Orders</h3>

<table class="admin-table">
<tr>
<th>Order ID</th>
<th>User</th>
<th>Total</th>
<th>Status</th>
</tr>

<?php
$recentOrders = $db->orders->find([], ['sort'=>['created_at'=>-1],'limit'=>10]);
foreach ($recentOrders as $o):
?>
<tr>
<td><?= (string)$o['_id'] ?></td>
<td><?= $o['user_name'] ?? '' ?></td>
<td>₹<?= number_format($o['total']) ?></td>
<td><?= $o['status'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<br><br>
<a class="btn-pill" href="restaurants.php">Manage Restaurants</a>
<a class="btn-pill-outline" href="orders.php">View All Orders</a>

</div>