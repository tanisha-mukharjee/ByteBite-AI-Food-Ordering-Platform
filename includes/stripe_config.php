<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Set your Stripe Secret Key (Test Mode)
\Stripe\Stripe::setApiKey('YOUR_STRIPE_SECRET_KEY');

$paymentIntent = \Stripe\PaymentIntent::create([
  'amount' => 50000,
  'currency' => 'inr',
]);