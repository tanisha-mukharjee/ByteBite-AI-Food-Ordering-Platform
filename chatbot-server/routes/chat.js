const express = require("express");
const router = express.Router();
const { getBotResponse } = require("../services/nlpService");

router.post("/", async (req, res) => {

    const message = req.body.message.toLowerCase();
    const menuCollection = req.menuCollection;

    console.log("MENU COLLECTION:", menuCollection ? "CONNECTED ✅" : "NOT CONNECTED ❌");
    console.log("USER MESSAGE:", message);

    // ===============================
    // 👋 GREETING
    // ===============================
    if (
        message.includes("hi") ||
        message.includes("hello") ||
        message.includes("hey")
    ) {
        return res.json({
            reply: "👋 Hi! Welcome to ByteBite!\n\nTry:\n👉 Add Pizza\n👉 Add Burger\n👉 Birthday for 5 people"
        });
    }

    // ===============================
    // 🧠 NLP
    // ===============================
    if (
        message.includes("birthday") ||
        message.includes("party") ||
        message.includes("anniversary") ||
        message.includes("under")
    ) {
        return res.json({
            reply: getBotResponse(message)
        });
    }

    // ===============================
    // 📚 FAQ
    // ===============================
    if (message.includes("delivery")) {
        return res.json({ reply: "🚚 Delivery takes 30–45 minutes." });
    }

    if (message.includes("payment")) {
        return res.json({ reply: "💳 We accept UPI, Cards & COD." });
    }

    if (message.includes("refund")) {
        return res.json({ reply: "🔁 Refunds in 3–5 days." });
    }

    // ===============================
    // 🛒 ADD TO CART
    // ===============================
    if (message.startsWith("add")) {

        let itemName = message.replace("add", "").trim();

        const item = await menuCollection.findOne({
            name: { $regex: itemName, $options: "i" }
        });

        console.log("ITEM FOUND:", item);

        if (item) {
            return res.json({
                reply: `🛒 ${item.name} added to your cart!`,
                action: {
                    id: item._id.toString(),
                    title: item.name,
                    price: item.price,
                    restaurant_id: item.restaurant_id || "default_restaurant"
                },
                showCartButton: true
            });
        }

        return res.json({
            reply: "❌ Item not found in menu"
        });
    }

    // ===============================
    // 🔥 FALLBACK
    // ===============================
    const fallback = await menuCollection.find().limit(1).toArray();

    if (fallback.length > 0) {
        const f = fallback[0];

        return res.json({
            reply: `🛒 ${f.name} added to cart!`,
            action: {
                id: f._id.toString(),
                title: f.name,
                price: f.price,
                restaurant_id: f.restaurant_id || "default_restaurant"
            },
            showCartButton: true
        });
    }

    // ===============================
    // 🤖 DEFAULT
    // ===============================
    return res.json({
        reply: "🤖 Try: Add Pizza, Add Burger 😊"
    });

});

module.exports = router;