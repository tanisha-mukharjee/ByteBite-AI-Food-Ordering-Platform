const express = require("express");
const router = express.Router();
const { getCart, clearCart } = require("../services/cartService");
const { v4: uuidv4 } = require("uuid");

router.post("/", (req, res) => {
    const { sessionId, address, paymentMethod } = req.body;

    const cart = getCart(sessionId);

    if (cart.length === 0) {
        return res.json({ message: "Cart is empty." });
    }

    let total = 0;
    cart.forEach(item => {
        total += item.price * item.quantity;
    });

    const order = {
        orderId: uuidv4(),
        items: cart,
        total,
        address,
        paymentMethod,
        estimatedDelivery: "30-45 minutes"
    };

    clearCart(sessionId);

    res.json({
        message: "🎉 Order placed successfully!",
        order
    });
});

module.exports = router;