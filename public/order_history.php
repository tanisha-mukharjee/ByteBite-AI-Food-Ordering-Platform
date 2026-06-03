<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../includes/db_connect.php';

// User must be logged in
if (empty($_SESSION['user'])) {
  header("Location: home.php?login=1");
  exit;
}

$user = $_SESSION['user'];

// FIX: handle both array and string session
if (is_array($user)) {
    $userId = $user['id'] ?? null;
} else {
    $userId = $user;
}

// Fetch user's orders (latest first)
$cursor = $ordersCollection->find(
  ['user_id' => $userId],
  ['sort' => ['created_at' => -1]]
);
?>

<div class="bb-container" style="margin-top:24px">
  <h2>Your Orders</h2>

  <?php if ($cursor->isDead()): ?>
    <p>No orders found.</p>
  <?php endif; ?>

  <div class="grid">
    <?php foreach ($cursor as $o): ?>

      <?php
      $created = $o['created_at'] instanceof MongoDB\BSON\UTCDateTime
        ? $o['created_at']->toDateTime()->format('Y-m-d H:i')
        : '';
      ?>

      <div class="rest-card">

        <!-- Reorder Button -->
        <form method="POST"
              action="/AI_Food_Order_System/public/reorder.php"
              style="margin-bottom:10px">
          <input type="hidden"
                 name="order_id"
                 value="<?= (string)$o['_id'] ?>">
          <button class="btn-pill-outline">
            Reorder
          </button>
        </form>

        <!-- Order Info -->
        <div class="rest-title">
          Order #<?= htmlspecialchars((string)$o['_id']) ?>
        </div>

        <div style="color:var(--muted)">
          <?= htmlspecialchars($created) ?>
          • <?= htmlspecialchars($o['status'] ?? 'placed') ?>
        </div>

        <div style="margin-top:10px">
          Items: <?= count($o['items'] ?? []) ?>
        </div>

        <div style="margin-top:12px;font-weight:700">
          Total: ₹<?= number_format($o['total'] ?? 0) ?>
        </div>

      </div>

    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
