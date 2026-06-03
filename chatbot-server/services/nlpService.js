// services/nlpService.js

function getBotResponse(message) {
    message = message.toLowerCase();

    // Extract numbers (budget or people)
    const numbers = message.match(/\d+/);
    const value = numbers ? parseInt(numbers[0]) : null;

    // 🎂 BIRTHDAY
    if (message.includes("birthday")) {
        if (value) {
            return `🎂 Birthday for ${value} people! I suggest:
            - 1 Cake
            - ${Math.ceil(value/2)} Pizzas
            - ${value} Drinks`;
        }
        return "🎂 For a birthday, I recommend Cake, Pizza, and Cold Drinks!";
    }

    // 💑 ANNIVERSARY
    if (message.includes("anniversary")) {
        return "💑 For a romantic anniversary: Pasta, Dessert, and Fresh Juice.";
    }

    // 🎉 PARTY / FRIENDS
    if (message.includes("party") || message.includes("friends")) {
        if (value) {
            return `🎉 Party for ${value} people? Try:
            - Burgers
            - Fries
            - ${value} Cold Drinks`;
        }
        return "🎉 Party combo: Burgers, Fries, and Coke!";
    }

    // 💰 BUDGET
    if (message.includes("under") && value) {
        if (value <= 300) {
            return "💰 Under ₹300: Snacks + Soft Drink";
        } else if (value <= 500) {
            return "💰 Under ₹500: Pizza + Drink";
        } else if (value <= 1000) {
            return "💰 Under ₹1000: Pizza + Garlic Bread + Drinks";
        } else {
            return "💰 Premium meal combo with desserts available!";
        }
    }

    // 🍕 FOOD SEARCH
    if (message.includes("pizza") || message.includes("burger")) {
        return "🍔 I found some great options for you! Check the menu section.";
    }

    // 👋 GREETING
    if (message.includes("hi") || message.includes("hello")) {
        return "Hello! 😊 Try typing 'Birthday', 'Party for 5', or 'Under 500'.";
    }

    // 🤖 DEFAULT
    return "🤖 I can help with Birthday, Party, Anniversary, or Budget-based suggestions!";
}

module.exports = { getBotResponse };