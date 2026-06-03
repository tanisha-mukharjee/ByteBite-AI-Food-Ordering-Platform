</main>

<footer class="bb-footer">
  <div class="bb-container">
    <div class="footer-left">
      <img src="/AI_Food_Order_System/assets/images/bytebite_logo.png"
           alt="ByteBite"
           class="logo small">
      <span>© <?=date("Y")?> ByteBite</span>
    </div>

    <div class="footer-right">
      <small>Made with ❤️ · ByteBite</small>
    </div>
  </div>
</footer>

<!-- Reorder Confirmation Modal -->
<div id="reorder-modal" class="bb-overlay">
  <div class="side-panel"
       style="max-width:420px;
              left:50%;
              top:50%;
              transform:translate(-50%,-50%);
              border-radius:16px;">
    <h3>Reorder items?</h3>
    <p>This will replace your current cart items.</p>
    <div style="display:flex;gap:10px;margin-top:16px;">
      <button class="btn-pill-outline"
              onclick="closeReorderModal()">
        Cancel
      </button>
      <button class="btn-pill"
              onclick="confirmReorderAction()">
        Yes, Reorder
      </button>
    </div>
  </div>
</div>


</body>
</html>
