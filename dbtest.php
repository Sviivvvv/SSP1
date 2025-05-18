<?php
$subscriptionProducts = getSubscriptionProducts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriptions</title>
    <link href="output.css" rel="stylesheet">
</head>

<body class="bg-white text-gray-800 px-6 py-8">
    <section>

        <h2 class="text-2xl font-semibold mb-2">Subscriptions</h2>

        <div class="space-y-10 max-w-5xl mx-auto">
            <?php foreach ($subscriptionProducts as $product): ?>
                <div class="flex flex-col items-start">
                    <!-- Card -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow-sm w-full">
                        <h3 class="text-lg font-semibold mb-1"><?= htmlspecialchars($product['ProductName']); ?></h3>
                        <p class="text-md font-medium mb-4">Rs.<?= number_format($product['price'], 0); ?>/Month</p>
                        <p class="mb-2 whitespace-pre-line"><?= htmlspecialchars($product['description']); ?></p>
                    </div>

                    <!-- Button outside the card -->
                    <form action="route.php" method="POST" class="mt-3">
                        <input type="hidden" name="action" value="addToCart" />
                        <input type="hidden" name="productID" value="<?= $product['productID']; ?>" />
                        <button type="submit"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                            Add to Cart
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</body>

</html>