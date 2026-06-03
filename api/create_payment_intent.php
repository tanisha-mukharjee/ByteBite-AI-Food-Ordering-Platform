<?php
session_start();
require_once '../includes/stripe_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['cart_total']) || $_SESSION['cart_total'] <= 0) {
    echo json_encode(['error' => 'Cart is empty']);
    exit;
}

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'mode' => 'payment',
        'line_items' => [[
            'price_data' => [
                'currency' => 'inr',
                'product_data' => [
                    'name' => 'Food Order - AI Food Ordering System',
                ],
                'unit_amount' => $_SESSION['cart_total'] * 100,
            ],
            'quantity' => 1,
        ]],
        'success_url' => 'http://localhost/AI_FOOD_ORDER_SYSTEM/public/payment_success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/AI_FOOD_ORDER_SYSTEM/public/checkout.php',
    ]);

    echo json_encode(['url' => $checkout_session->url]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}