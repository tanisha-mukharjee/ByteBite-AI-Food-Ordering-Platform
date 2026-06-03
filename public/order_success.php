<?php require_once __DIR__ . '/header.php'; ?>
<div class="bb-container" style="margin-top:26px;">
  <div class="rest-card" style="max-width:640px;margin:auto;text-align:center">
    <h2>Order placed</h2>
    <p>Thank you! Your order is confirmed.</p>
    <p>Order ID: <strong><?=htmlspecialchars($_GET['order_id'] ?? '')?></strong></p>
    <p><a class="btn-pill" href="/AI_Food_Order_System/public/order_history.php">View orders</a></p>
  </div>
</div>

<script>localStorage.removeItem('bb_cart'); updateCartUI();</script>
<?php require_once __DIR__ . '/footer.php';
 ?>
