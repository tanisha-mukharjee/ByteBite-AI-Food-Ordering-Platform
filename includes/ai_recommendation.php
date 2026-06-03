<?php

function getAIRecommendations(
    $userId,
    $ordersCollection,
    $menuCollection,
    $limit = 6
) {

    // ✅ 0. Handle guest user (no login)
    if (!$userId) {
        return $menuCollection->find([], ['limit' => $limit]);
    }

    // 1️⃣ Fetch user order history
    $orders = $ordersCollection->find(
        ['user_id' => $userId],
        ['sort' => ['created_at' => -1], 'limit' => 20]
    );

    $dishFrequency = [];

    foreach ($orders as $order) {

        if (!isset($order['items'])) continue;

        foreach ($order['items'] as $item) {

            $name = strtolower($item['name'] ?? '');
            if (!$name) continue;

            $qty = intval($item['qty'] ?? 1);

            $dishFrequency[$name] = ($dishFrequency[$name] ?? 0) + $qty;
        }
    }

    // 2️⃣ If no history → fallback (popular items)
    if (empty($dishFrequency)) {
        return $menuCollection->find([], ['limit' => $limit]);
    }

    // 3️⃣ Sort by frequency
    arsort($dishFrequency);
    $topDishes = array_slice(array_keys($dishFrequency), 0, 5);

    // 4️⃣ Get categories of top dishes
    $categories = [];

    $menuItems = $menuCollection->find([
        'name' => [
            '$in' => array_map(function($d) {
                return new MongoDB\BSON\Regex($d, 'i');
            }, $topDishes)
        ]
    ]);

    foreach ($menuItems as $item) {
        if (!empty($item['category'])) {
            $categories[] = $item['category'];
        }
    }

    // 5️⃣ Category-based recommendation (SMART AI 🔥)
    if (!empty($categories)) {

        $recommended = $menuCollection->find(
            [
                'category' => ['$in' => $categories]
            ],
            ['limit' => $limit]
        );

        return $recommended;
    }
    if (iterator_count($recommended) === 0) {
    return $menuCollection->aggregate([
        ['$sample' => ['size' => $limit]]
    ]);
}

    // 6️⃣ Fallback to name-based regex
    $regex = array_map(function($d) {
        return new MongoDB\BSON\Regex($d, 'i');
    }, $topDishes);

    $recommended = $menuCollection->aggregate([
    [
        '$match' => [
            'category' => ['$in' => $categories]
        ]
    ],
    [
        '$sample' => ['size' => $limit]
    ]
]);

    // 7️⃣ Final fallback
    if (!$recommended) {
        return $menuCollection->find([], ['limit' => $limit]);
    }

    return $recommended;
}