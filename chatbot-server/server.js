const express = require("express");
const cors = require("cors");
const { MongoClient } = require("mongodb");

const chatRoutes = require("./routes/chat");

const app = express();

app.use(cors({
    origin: "http://localhost",
    credentials: true
}));

app.use(express.json());

const uri = "mongodb+srv://mukharjeetanisha05_db_user:Tanisha123@cluster0.591exvf.mongodb.net/?appName=Cluster0";
const client = new MongoClient(uri);

let menuCollection;

async function startServer() {
    try {
        await client.connect();
        console.log("✅ MongoDB Connected");

        const db = client.db("bytebite_db");
        menuCollection = db.collection("menu");

        app.use("/chat", (req, res, next) => {
            req.menuCollection = menuCollection;
            next();
        }, chatRoutes);

        const PORT = 5000;
        app.listen(PORT, () => {
            console.log(`🤖 ByteBite Chatbot running on port ${PORT}`);
        });

    } catch (err) {
        console.error("❌ MongoDB connection failed:", err);
    }
}

startServer();