function checkFAQ(message) {
    message = message.toLowerCase();

    if (message.includes("delivery")) {
        return "⏱️ Delivery usually takes 30-45 minutes.";
    }
    if (message.includes("payment")) {
        return "💳 We accept UPI, Cards and Cash on Delivery.";
    }
    if (message.includes("refund")) {
        return "🔄 Refunds are processed in 5-7 working days.";
    }
    if (message.includes("hours")) {
        return "🕒 We are open from 10 AM to 11 PM.";
    }

    return null;
}

module.exports = { checkFAQ };