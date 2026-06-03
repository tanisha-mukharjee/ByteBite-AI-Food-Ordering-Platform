<?php
require_once __DIR__ . '/../vendor/autoload.php';
use MongoDB\Client;

try {
    $mongoUri = getenv('MONGODB_URI') ?: 'ADD MONGODB_URI';
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
