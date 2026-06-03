<?php
require_once __DIR__ . '/header.php';

session_start();

/* ===============================
   LOGIN CHECK
================================ */
if (empty($_SESSION['user'])) {
    header("Location: home.php?login=1");
    exit;
}

/* ===============================
   CART CHECK (SESSION BASED)
================================ */
if (empty($_SESSION['cart']['items'])) {
    echo "<script>alert('Cart empty'); window.location.href='home.php';</script>";
    exit;
}

$cartItems = $_SESSION['cart']['items'];
$total = 0;

foreach ($cartItems as $item) {
    $total += $item['price'] * $item['qty'];
}

/* Save total for Stripe */
$_SESSION['cart_total'] = $total;
?>

<div class="checkout-wrapper bb-container">

  <!-- ORDER SUMMARY -->
  <div class="checkout-card">

    <h2>Order Summary</h2>

    <?php foreach ($cartItems as $item): ?>
      <div class="summary-row">
        <div>
          <?= htmlspecialchars($item['name']) ?>
          <span class="muted">x <?= (int)$item['qty'] ?></span>
        </div>
        <div>₹<?= number_format($item['price'] * $item['qty']) ?></div>
      </div>
    <?php endforeach; ?>

    <div class="summary-total">
      <span>Total</span>
      <strong>₹<?= number_format($total) ?></strong>
    </div>

  </div>

  <!-- DELIVERY FORM -->
  <div class="checkout-card">

    <h2>Delivery Details</h2>

    <form id="checkout-form">

      <div class="form-group">
        <label>Delivery Address</label>
        <textarea 
          name="address"
          class="modern-input"
          required><?= htmlspecialchars($_SESSION['user']['address'] ?? '') ?></textarea>
      </div>

      <button type="button" id="checkout-button" class="btn-checkout">
        Pay & Place Order
      </button>

    </form>

  </div>

</div>

<!-- DELIVERY MAP -->
<h3 style="margin-top:30px;">Delivery Location</h3>

<div id="delivery-map"
     style="height:300px;
            border-radius:12px;
            overflow:hidden;
            margin-top:10px;">
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

  const restaurantLatLng = [19.0760, 72.8777]; // Mumbai
  const userLatLng = [19.0896, 72.8656];       // Demo user location

  const map = L.map('delivery-map').setView(restaurantLatLng, 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(map);

  L.marker(restaurantLatLng).addTo(map).bindPopup("Restaurant");
  L.marker(userLatLng).addTo(map).bindPopup("Your Location");

  const route = L.polyline(
    [restaurantLatLng, userLatLng],
    { color: '#ff6a00', weight: 4 }
  ).addTo(map);

  map.fitBounds(route.getBounds());

});

/* ===============================
   STRIPE CHECKOUT
================================ */
document.getElementById("checkout-button").addEventListener("click", async function() {

    const address = document.querySelector("textarea[name='address']").value;

    if (!address.trim()) {
        alert("Please enter delivery address.");
        return;
    }

    // Save address in session
    await fetch("../api/save_address.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ address: address })
    });

    // Create Stripe checkout session
    const response = await fetch("../api/create_checkout_session.php", {
        method: "POST"
    });

    const data = await response.json();

    if (data.url) {
        window.location.href = data.url;
    } else {
        alert("Payment initialization failed.");
    }
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>