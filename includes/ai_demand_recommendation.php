<?php
function predictTodayDemand($ordersCollection) {
  $hour = (int)date('H');
  $foodCount = [];

  foreach ($ordersCollection->find() as $order) {
    foreach ($order['items'] ?? [] as $item) {
      $name = $item['title'];
      $foodCount[$name] = ($foodCount[$name] ?? 0) + $item['qty'];
    }
  }

  if (empty($foodCount)) return "Not enough data";

  arsort($foodCount);
  return array_key_first($foodCount);
}
