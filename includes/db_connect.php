<?php
require_once __DIR__ . '/../vendor/autoload.php';
use MongoDB\Client;

try {
    $mongoUri = getenv('MONGODB_URI') ?: 'mongodb+srv://mukharjeetanisha05_db_user:Tanisha123@cluster0.591exvf.mongodb.net/?appName=Cluster0';
    $dbName = getenv('MONGODB_DB') ?: 'bytebite_db';
    $client = new Client($mongoUri);
    $db = $client->selectDatabase($dbName);

    $usersCollection = $db->users;
    $restaurantsCollection = $db->restaurants;
    $menuCollection = $db->menu;
    $ordersCollection = $db->orders;

    // index for unique emails
    $usersCollection->createIndex(['email' => 1], ['unique' => true]);

} catch (Exception $e) {
    die("MongoDB connection error: " . $e->getMessage());
}
