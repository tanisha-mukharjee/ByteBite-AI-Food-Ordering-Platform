<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
$hideHeader = $hideHeader ?? false;
$pageClass = $pageClass ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ByteBite — Order Food</title>

<link rel="icon" type="image/png" href="/AI_Food_Order_System/assets/images/bytebite_logo.png">
  <!-- Main App CSS -->
  <link rel="stylesheet" href="/AI_Food_Order_System/assets/css/style.css">

  <!-- Leaflet (OpenStreetMap – Local files, FREE) -->
  <link rel="stylesheet" href="/AI_Food_Order_System/assets/leaflet/leaflet.css">
  <script src="/AI_Food_Order_System/assets/leaflet/leaflet.js"></script>
  <!-- Main App JS (REQUIRED for cart, login, buttons) -->
<script src="/AI_Food_Order_System/assets/js/main.js" defer></script>

</head>

<body class="<?= htmlspecialchars($pageClass) ?>">

<?php if (!$hideHeader): ?>
<header class="bb-header bb-header-glass" id="main-header">
  <div class="bb-container header-inner">

    <!-- Brand -->
    <a class="brand" href="/AI_Food_Order_System/public/home.php">
      <img src="/AI_Food_Order_System/assets/images/bytebite_logo.png"
           class="logo logo-lg"
           alt="ByteBite">
      <span class="brand-text brand-text-lg">ByteBite</span>
    </a>

    <!-- Search -->
    <div class="search-wrap">
      <input id="global-search"
             class="search-input"
             placeholder="Search restaurants, dishes or cuisines">
      <button class="search-btn" onclick="onSearch()">🔍</button>
    </div>

    <!-- Navigation -->
    <nav class="nav">
      <a href="/AI_Food_Order_System/public/menu.php">Menu</a>

      <?php if ($user): ?>
        <a href="/AI_Food_Order_System/public/order_history.php">Orders</a>
        <a href="/AI_Food_Order_System/public/profile.php">
          Hi, <?= htmlspecialchars(
    is_array($user)
        ? ($user['name'] ?? $user['email'] ?? 'User')
        : $user
) ?>
        </a>

        <a class="btn-pill-outline"
           href="/AI_Food_Order_System/auth/logout.php">
          Logout
        </a>
      <?php else: ?>
        <button class="btn-pill-outline" onclick="openLogin()">Login</button>
        <a class="btn-pill"
           href="/AI_Food_Order_System/public/register.php">
          Sign up
        </a>
      <?php endif; ?>
    </nav>

  </div>
</header>
<?php endif; ?>


<!-- Overlay -->
<div id="bb-overlay"
     class="bb-overlay"
     onclick="closeOverlays()"></div>

<!-- Login slide panel -->
<aside id="login-panel" class="side-panel">
  <button class="close-panel" onclick="closeLogin()">✕</button>

  <h3>Welcome back</h3>

  <a class="google-btn"
     href="/AI_Food_Order_System/auth/google-connect.php">
    <img src="/AI_Food_Order_System/assets/images/google.svg"
         alt="Google"
         width="20">
    Continue with Google
  </a>

  <?php if (!empty($_SESSION['login_error'])): ?>
    <div class="flash error">
      <?= htmlspecialchars($_SESSION['login_error']) ?>
    </div>
    <?php unset($_SESSION['login_error']); ?>
  <?php endif; ?>

  <form method="POST"
        action="/AI_Food_Order_System/auth/login_process.php"
        class="login-form">
    <input name="email"
           type="email"
           class="input"
           placeholder="Email"
           required>

    <input name="password"
           type="password"
           class="input"
           placeholder="Password"
           required>

    <button class="btn-pill" type="submit">Login</button>
  </form>

  <p class="muted">
    Don't have an account?
    <a href="/AI_Food_Order_System/public/register.php">
      Create one
    </a>
  </p>
</aside>

<!-- Cart slide panel -->
<aside id="cart-panel" class="side-panel right">
  <button class="close-panel" onclick="closeCart()">✕</button>

  <h3>Your cart</h3>

  <div id="cart-items" class="cart-items"></div>

  <div class="cart-footer">
    <div class="cart-total">
      Total: ₹<span id="cart-total">0</span>
    </div>

    <a class="btn-pill"
       href="/AI_Food_Order_System/public/checkout.php">
      Checkout
    </a>
  </div>
</aside>
<div id="chat-panel" class="floating-chat">

  <button class="close-panel" onclick="closeChat()">✕</button>

  <h3>BYTEBITE Assistant 🤖</h3>

  <div id="chatBody" class="chat-body"></div>



  <div class="chat-input">
      <input type="text" id="userMessage" placeholder="Type your message...">
      <button onclick="sendMessage()">Send</button>
  </div>

</div>
<script>
    const USER_SESSION_ID = "<?= session_id(); ?>";
</script>
<!-- Floating Chat Button -->
<button id="chat-toggle-btn" onclick="openChat()">
  🤖
</button>
<!-- Floating Cart Button -->
<button id="float-cart-btn" onclick="openCart()">
  🛒 <span id="float-cart-count">0</span>
</button>
