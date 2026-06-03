// ByteBite main JS
document.addEventListener('DOMContentLoaded', () => {
  updateCartUI();

  const params = new URLSearchParams(window.location.search);
  if (params.get('login') === '1') openLogin();

  const input = document.getElementById("userMessage");
  if (input) {
    input.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        e.preventDefault();
        sendMessage();
      }
    });
  }
});

function qs(sel) { return document.querySelector(sel); }

// ================= UI =================
function openLogin() {
  qs('#bb-overlay').classList.add('show');
  qs('#login-panel').classList.add('show');
}

function closeLogin() {
  qs('#bb-overlay').classList.remove('show');
  qs('#login-panel').classList.remove('show');
}

function closeOverlays() {
  closeLogin();
  closeCart();
}

// ================= CART =================
function updateCartUI() {
  fetch('/AI_Food_Order_System/api/cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'action=count'
  })
    .then(res => res.json())
    .then(data => {
      const badge = document.getElementById('float-cart-count');
      if (badge) badge.innerText = data.count;
    });
}

function addToCart(itemId, name, price, restaurantId) {

  console.log("ADDING:", itemId, restaurantId);

  fetch('/AI_Food_Order_System/api/cart.php', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=add&item_id=${itemId}&restaurant_id=${restaurantId}`
  })
    .then(res => res.json())
    .then(data => {
      console.log("SERVER:", data);

      if (data.status === 'success') {
        updateCartUI();
        loadCart();
      } else {
        alert("❌ Failed to add to cart");
      }
    });
}

function loadCart() {
  fetch('/AI_Food_Order_System/api/cart.php', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'action=get'
  })
    .then(res => res.json())
    .then(data => {

      const cartItems = document.getElementById('cart-items');
      const cartTotal = document.getElementById('cart-total');

      if (!cartItems || !cartTotal) return;

      cartItems.innerHTML = '';

      if (!data.items || data.items.length === 0) {
        cartItems.innerHTML = '<p>Your cart is empty.</p>';
        cartTotal.innerText = '0';
        return;
      }

      let total = 0;

      data.items.forEach(item => {
        total += item.price * item.quantity;

        cartItems.innerHTML += `
          <div class="cart-row">
            <div>${item.name}</div>
            <div>x${item.quantity}</div>
            <div>₹${item.price}</div>
          </div>
        `;
      });

      cartTotal.innerText = total;
    });
}

function openCart() {
  document.getElementById('cart-panel').classList.add('open');
  document.getElementById('bb-overlay').classList.add('active');
  loadCart();
}

function closeCart() {
  document.getElementById('cart-panel').classList.remove('open');
  document.getElementById('bb-overlay').classList.remove('active');
}

// ================= CHAT =================
function openChat() {
  const chat = document.getElementById("chat-panel");
  if (!chat) return;

  chat.classList.add("show");
  document.getElementById("chatBody").innerHTML = "";
}

function closeChat() {
  const chat = document.getElementById("chat-panel");
  if (!chat) return;
  chat.classList.remove("show");
}

// ================= SEND MESSAGE =================
async function sendMessage() {

  const input = document.getElementById("userMessage");
  const chatBody = document.getElementById("chatBody");

  const message = input.value.trim();
  if (!message) return;

  // Show user message
  chatBody.innerHTML += `
    <div style="text-align:right;margin:6px 0;">
      <span style="background:#ff6a00;color:#fff;padding:6px 10px;border-radius:12px;">
        ${message}
      </span>
    </div>
  `;

  input.value = "";

  // Greeting UI
  if (["hi", "hello", "hey"].includes(message.toLowerCase())) {
    showMainOptions();
    chatBody.scrollTop = chatBody.scrollHeight;
    return;
  }

  try {

    const response = await fetch("http://localhost:5000/chat", {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message })
    });

    const data = await response.json();

    // Bot reply
    chatBody.innerHTML += `
      <div style="margin:6px 0;">
        <div style="background:#333;color:#fff;padding:8px 12px;border-radius:14px;">
          ${data.reply}
        </div>
      </div>
    `;

    // ✅ ADD TO CART FROM CHATBOT
    if (data.action) {
      console.log("CHAT ACTION:", data.action);

      addToCart(
        data.action.id,
        data.action.title,
        data.action.price,
        data.action.restaurant_id || "default_restaurant"
      );
    }

    // Show cart button
    if (data.showCartButton) {
      chatBody.innerHTML += `
        <div style="margin-top:10px;">
          <button onclick="openCart()" 
            style="background:#ff6a00;color:#fff;padding:10px 16px;border:none;border-radius:20px;">
            Open Cart 🛒
          </button>
        </div>
      `;
    }

    chatBody.scrollTop = chatBody.scrollHeight;

  } catch (error) {
    chatBody.innerHTML += `
      <div style="color:red;margin:6px 0;">
        Server error ❌
      </div>
    `;
  }
}

// ================= BOT OPTIONS =================
function showMainOptions() {
  const chatBody = document.getElementById("chatBody");

  chatBody.innerHTML += `
    <div style="margin:8px 0;">
      <div style="background:#333;color:#fff;padding:8px 12px;border-radius:14px;">
        Hi 👋 What can I help you with today?
      </div>
    </div>

    <div class="chat-options">
      <button onclick="openCart()">🧾 Check Cart</button>
      <button onclick="showDelivery()">🚚 Delivery</button>
      <button onclick="showPayment()">💳 Payment</button>
    </div>
  `;

  chatBody.scrollTop = chatBody.scrollHeight;
}

// ================= BOT HELPERS =================
function showDelivery() {
  addBotMessage("🚚 Delivery takes 30–45 minutes.");
}

function showPayment() {
  addBotMessage("💳 We accept UPI, Cards & COD.");
}

function addBotMessage(text) {
  const chatBody = document.getElementById("chatBody");
  chatBody.innerHTML += `
    <div style="margin:6px 0;">
      <div style="background:#333;color:#fff;padding:8px 12px;border-radius:14px;">
        ${text}
      </div>
    </div>
  `;
  chatBody.scrollTop = chatBody.scrollHeight;
}