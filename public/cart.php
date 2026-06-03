<?php require_once __DIR__ . '/header.php'; ?>
<div class="bb-container" style="margin-top:20px;">
  <div class="rest-card" style="max-width:760px;margin:auto">
    <h2>Your Cart</h2>
    <div id="cart-items" class="cart-items"></div>
    <div style="margin-top:16px;display:flex;justify-content:space-between;align-items:center">
      <div><a class="btn-pill-outline" href="/AI_Food_Order_System/public/menu.php">Continue shopping</a></div>
      <div><a class="btn-pill" href="/AI_Food_Order_System/public/checkout.php">Proceed to checkout</a></div>
    </div>
  </div>
</div>

<script>renderCartItems();</script>
<?php require_once __DIR__ . '/footer.php'; ?>
