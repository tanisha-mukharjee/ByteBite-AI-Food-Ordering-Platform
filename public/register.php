<?php
$hideHeader = true;
require_once __DIR__ . '/header.php';?>
<div class="auth-page">
  <div class="auth-card">

    <h2>Create an account</h2>

    <?php if(!empty($_SESSION['register_error'])): ?>
      <div class="flash error">
        <?=htmlspecialchars($_SESSION['register_error']); unset($_SESSION['register_error']);?>
      </div>
    <?php endif; ?>

    <form method="POST" action="/AI_Food_Order_System/auth/register_process.php">
      <input name="name" class="input" placeholder="Full name" required>
      <input name="email" type="email" class="input" placeholder="Email" required>
      <input name="password" type="password" class="input" placeholder="Password" required>
      <button class="btn-pill" type="submit">Create account</button>
    </form>

    <p class="muted">
      Already have an account?
      <a href="#" onclick="openLogin();return false;">Login</a>
    </p>

  </div>
</div>

<?php require_once __DIR__ . '/footer.php';?>
