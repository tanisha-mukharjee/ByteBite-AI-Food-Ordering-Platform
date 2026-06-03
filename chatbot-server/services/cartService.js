let carts = {};

function getCart(sessionId) {
    if (!carts[sessionId]) {
        carts[sessionId] = [];
    }
    return carts[sessionId];
}

function addToCart(sessionId, item) {
    const cart = getCart(sessionId);

    const existing = cart.find(i => i.id === item.id);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({ ...item, quantity: 1 });
    }

    return cart;
}

function clearCart(sessionId) {
    carts[sessionId] = [];
}

module.exports = { getCart, addToCart, clearCart };