<?php
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/header.php';

$restaurantsCollection = $db->restaurants;
$menuCollection = $db->menu;

$q = trim($_GET['q'] ?? '');
$restId = $_GET['rest'] ?? '';

$filter = [];

/* 🔎 Search Filter */
if ($q) {
    $filter['$text'] = ['$search' => $q];
}

/* 🏪 Restaurant Filter */
if ($restId) {
    try {
        $filter['restaurant_id'] = new MongoDB\BSON\ObjectId($restId);
    } catch (Exception $e) {
        die("Invalid Restaurant ID");
    }
}

/* Only show available items */
$filter['is_available'] = true;

/* Fetch Menu */
$cursor = $menuCollection->find([]);
?>

<div class="bb-container" style="margin-top:20px;">
  
  <h2>
    Menu 
    <?php if($q): ?>
      for "<?= htmlspecialchars($q) ?>"
    <?php endif; ?>
  </h2>

  <div class="grid">
    
    <?php foreach($cursor as $m): ?>
      
      <div class="rest-card">

        <!-- 🍕 Menu Image -->
        <div class="menu-item-card">

  <img 
  src="<?= str_replace('AI_FOOD_ORDER_SYSTEM', 'AI_Food_Order_System', $m['image']) ?>" 
  class="menu-thumb"
  alt="<?= htmlspecialchars($m['name']) ?>"
  onerror="this.src='/AI_Food_Order_System/assets/images/pizza.jpg'"
>
  <div class="menu-content">
      <div class="menu-title"><?= htmlspecialchars($m['name']) ?></div>
      <div class="menu-price">₹<?= number_format($m['price']) ?></div>

      <button 
  class="btn-pill-outline"
  onclick="addToCart(
    '<?= (string)$m['_id'] ?>',
    '<?= htmlspecialchars($m['name']) ?>',
    <?= intval($m['price']) ?>,
    '<?= htmlspecialchars($restId) ?>'
  )">
  Add
</button>
  </div>

</div>
       

      </div>

    <?php endforeach; ?>

  </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>