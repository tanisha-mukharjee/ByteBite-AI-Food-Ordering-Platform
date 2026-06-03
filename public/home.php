<?php
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/ai_recommendation.php';
require_once __DIR__ . '/header.php';
?>

<!-- ================= HERO SECTION ================= -->
<section class="hero">
  <div class="bb-container hero-inner">

    <div class="hero-left">
      <h1>Order food & groceries. Fast & fresh.</h1>
      <p>Local favourites, exclusive deals & lightning delivery.</p>

      <p style="margin-top:16px;">
        <a class="btn-pill" href="/AI_Food_Order_System/public/menu.php">
          Browse restaurants
        </a>
      </p>

      <div class="category-row">
        <?php
        $cats = [
          ['img'=>'pizza.jpg','label'=>'Pizza'],
          ['img'=>'Burger.jpg','label'=>'Burgers'],
          ['img'=>'biryani.jpg','label'=>'Biryani'],
          ['img'=>'fries.jpg','label'=>'Fries'],
          ['img'=>'pasta.jpg','label'=>'Pasta'],
        ];
        foreach ($cats as $c): ?>
          <div class="cat-card">
            <img src="/AI_Food_Order_System/assets/images/<?= $c['img'] ?>">
            <div class="cat-label"><?= $c['label'] ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="hero-right">
      <img src="/AI_Food_Order_System/assets/images/pizza.jpg">
    </div>

  </div>
</section>

<!-- ================= AI RECOMMENDATIONS ================= -->
<?php if (!empty($_SESSION['user'])): ?>
<div class="bb-container">
  <h2 style="margin-top:32px">Recommended for you 🤖</h2>

  <?php
  $user = $_SESSION['user'] ?? null;
  $userId = is_array($user) ? ($user['id'] ?? null) : null;

  $aiItems = getAIRecommendations(
    $userId,
    $ordersCollection,
    $menuCollection
  );

  $hasAI = false;
  ?>

  <!-- ✅ AI ITEMS -->
  <div class="ai-carousel">
  <?php foreach ($aiItems as $item): $hasAI = true; ?>

    <div class="ai-card" style="position:relative;">

      <!-- 🔥 Trending -->
      <div style="
        position:absolute;
        top:8px;
        right:8px;
        background:black;
        color:white;
        font-size:10px;
        padding:3px 6px;
        border-radius:6px;
      ">
        🔥 Trending
      </div>

      <img 
        src="<?= htmlspecialchars($item['image']) ?>" 
        style="width:100%; height:140px; object-fit:cover; border-radius:10px;"
      >

      <div style="
        background:#ff6b00;
        color:white;
        font-size:11px;
        padding:4px 8px;
        border-radius:12px;
        margin-top:6px;
        display:inline-block;
      ">
        🤖 AI Pick
      </div>

      <strong><?= htmlspecialchars($item['name']) ?></strong>

      <div style="font-size:13px; color:#666;">
        ⭐ 4.5 • 30 mins
      </div>

      <div>₹<?= number_format($item['price']) ?></div>
      <button class="btn-pill" onclick="addToCart(
  '<?= (string)$item['_id'] ?>',
  '<?= addslashes($item['name']) ?>',
  <?= intval($item['price']) ?>,
  '<?= isset($item['restaurant_id']) ? (string)$item['restaurant_id'] : '' ?>'
)">
  Add
</button>
      
    </div>

  <?php endforeach; ?>
  </div>

  <!-- ✅ FALLBACK -->
  <?php if (!$hasAI): ?>
    <p style="margin-top:12px;color:#777;">
      No order history yet — showing popular items 🍕
    </p>

    <div class="ai-carousel">
    <?php
    $popular = $menuCollection->find([], ['limit'=>5]);
    foreach ($popular as $item):
    ?>

      <div class="ai-card">

        <img 
          src="<?= htmlspecialchars($item['image']) ?>" 
          style="width:100%; height:140px; object-fit:cover; border-radius:10px;"
        >

        <strong><?= htmlspecialchars($item['name']) ?></strong>

        <div style="font-size:13px; color:#666;">
          ⭐ 4.3 • 30 mins
        </div>

        <div>₹<?= number_format($item['price']) ?></div>
        <button class="btn-pill" onclick="addToCart(
  '<?= (string)$item['_id'] ?>',
  '<?= addslashes($item['name']) ?>',
  <?= intval($item['price']) ?>,
  '<?= isset($item['restaurant_id']) ? (string)$item['restaurant_id'] : '' ?>'
)">
  Add
</button>

      </div>

    <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>
<?php endif; ?>

<!-- ================= FEATURED RESTAURANTS ================= -->
<div class="bb-container">
  <h2 style="margin-top:36px">Featured restaurants</h2>

  <?php
  $cursor = $restaurantsCollection->find([], [
    'limit' => 8,
    'sort'  => ['_id' => -1]
  ]);
  ?>

  <div class="restaurant-grid-wrapper">
    <?php foreach ($cursor as $r): ?>

      <div class="restaurant-card-grid">

        <img 
          src="<?= htmlspecialchars($r['image']) ?>" 
          style="width:100%; height:140px; object-fit:cover; border-radius:10px;"
        >

        <div class="restaurant-content">

          <div class="restaurant-title">
            <?= htmlspecialchars($r['name']) ?>
          </div>

          <div class="restaurant-meta">
            <?= htmlspecialchars($r['cuisine'] ?? '') ?>
            • <?= htmlspecialchars($r['eta'] ?? '30-40 min') ?>
          </div>

          <div style="margin-top:12px;">
            <button class="btn-pill-outline"
              onclick="location.href='/AI_Food_Order_System/public/menu.php?rest=<?= urlencode((string)$r['_id']) ?>'">
              View Menu
            </button>
          </div>

        </div>

      </div>

    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>