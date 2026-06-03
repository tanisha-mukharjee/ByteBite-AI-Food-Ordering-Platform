const express = require("express");
const router = express.Router();
const { getCart } = require("../services/cartService");

router.get("/:sessionId", (req, res) => {
    const cart = getCart(req.params.sessionId);
    res.json(cart);
});

module.exports = router;