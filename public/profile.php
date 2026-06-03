    <?php
    require_once __DIR__ . '/header.php';
    require_once __DIR__ . '/../includes/db_connect.php';
    if (empty($_SESSION['user'])) { header("Location: home.php?login=1"); exit; }
    $user = $_SESSION['user'];
    $doc = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($user['id'])]);
    ?>
    <div class="container" style="margin-top:20px;">
    <div class="card" style="max-width:720px;margin:auto;">
        <h2>Profile</h2>
        <form method="POST" action="update_profile.php">
        <input name="name" class="input" value="<?=htmlspecialchars($doc['name'] ?? '')?>" required>
        <input name="phone" class="input" value="<?=htmlspecialchars($doc['phone'] ?? '')?>" placeholder="Phone">
        <textarea name="address" class="input" rows="3" placeholder="Address"><?=htmlspecialchars($doc['address'] ?? '')?></textarea>
        <button class="btn-pill">Save</button>
        </form>
    </div>
    </div>
    <?php require_once __DIR__ . '/footer.php';?>
