<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/stripe_config.php';

if (!isset($_GET['session_id'])) {
    header("Location: checkout.php");
    exit;
}

$session_id = $_GET['session_id'];

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    if ($session->payment_status === 'paid') {

        $order = [
            "user_id" => $_SESSION['user_id'],
            "items" => $_SESSION['cart'],
            "total" => $_SESSION['cart_total'],
            "payment_status" => "Paid",
            "stripe_session_id" => $session_id,
            "created_at" => new MongoDB\BSON\UTCDateTime()
        ];

        $db->orders->insertOne($order);

        unset($_SESSION['cart']);
        unset($_SESSION['cart_total']);

        echo "<h2>Payment Successful! Order Placed.</h2>";

    } else {
        echo "Payment not completed.";
    }

} catch (Exception $e) {
    echo "Error verifying payment.";
}