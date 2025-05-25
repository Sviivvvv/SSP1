<?php
include_once 'functions.php';

$filters = $_SESSION['orderFilters'] ?? [];
$userID = $filters['userID'] ?? null;
$orderDate = $filters['orderDate'] ?? null;

$orders = adminFilterOrders($userID, $orderDate);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>adminOrders</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="./src/js/main.js" defer></script>
    <script src="./src/js/adminOrders.js" defer></script>
    <style>
        body {
            font-family: 'Garamond', serif;
        }

        div::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-[#122C4F] text-[#FBF9E4] min-h-screen overflow-x-hidden max-w-screen">
    <div>
        <header class="w-full px-8 py-4 relative">
            <!-- Logo -->
            <div class="absolute mt-3 top-4 left-6">
                <p class="text-xl sm:text-3xl md:text-4xl font-bold">Scent Memoir</p>
            </div>

            <!-- Nav bar -->
            <nav class="mt-20">
                <div class="hidden md:flex justify-end space-x-14">
                    <a href="/luxuryperfumestore/adminDashboard-Products"
                        class="text-md font-bold hover:underline transition-colors duration-200">Manage Products</a>
                    <a href="/luxuryperfumestore/adminDashboard-Users"
                        class="text-md font-bold hover:underline transition-colors duration-200">Manage Users</a>
                    <a href="/luxuryperfumestore/adminDashboard-Orders"
                        class="text-md font-bold hover:underline transition-colors duration-200">View Orders</a>

                    <?php if (isLoggedIn()): ?>
                        <a href="/luxuryperfumestore/logout.php"
                            class="text-md font-bold hover:underline transition-colors duration-200">Logout</a>
                    <?php else: ?>
                        <a href="/luxuryperfumestore/login.php"
                            class="text-md font-bold hover:underline transition-colors duration-200">Login</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile nav button -->
                <div class="md:hidden flex justify-end">
                    <button id="burgerButton" class="focus:outline-none relative group">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-br from-[#FBF9E4]/20 to-[#FBF9E4]/40 
                        scale-0 group-hover:scale-100 group-active:scale-110 opacity-0 
                        group-hover:opacity-100 group-active:opacity-100 transition-all duration-1000-m-3 blur-[2px]">
                        </div>
                        <img src="./src/otherPics/mobileNavIcon.png" alt="mobileNavIcon"
                            class="relative w-13 h-13 transition-transform duration-300 group-hover:scale-110 z-10">
                    </button>
                </div>

                <!-- Mobile nav -->
                <div id="mobileNav" class="hidden md:hidden overflow-hidden">
                    <div class="flex flex-col items-end space-y-3 mt-4 px-4">
                        <a href="/luxuryperfumestore/adminDashboard-Products"
                            class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300">Manage
                            Products</a>
                        <a href="/luxuryperfumestore/adminDashboard-Users"
                            class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-75">Manage
                            Users</a>
                        <a href="/luxuryperfumestore/adminDashboard-Orders"
                            class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-100">View
                            Orders</a>


                        <?php if (isLoggedIn()): ?>
                            <a href="/luxuryperfumestore/logout.php"
                                class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-200">Logout</a>
                        <?php else: ?>
                            <a href="/luxuryperfumestore/login.php"
                                class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-200">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </header>
    </div>

    <!-- admin orders section -->

    <section class="flex justify-center mt-30 mb-30">
        <div class="w-full max-w-5xl p-4 bg-[#FBF9E4] rounded-2xl shadow-xl">
            <h2 class="text-xl font-extrabold text-[#122C4F] mb-5 text-center">Order Summary</h2>

            <!-- orders header -->
            <form id="filterForm" class="mb-5 flex flex-wrap gap-4 justify-center items-end">
                <div>
                    <label class="text-[#122C4F] font-bold text-sm">User ID:</label>
                    <input type="number" name="userID" value="<?= htmlspecialchars($userID) ?>"
                        class="border p-1 rounded text-[#122C4F]" placeholder="Filter by User ID">
                </div>
                <div>
                    <label class="text-[#122C4F] font-bold text-sm">Order Date:</label>
                    <input type="date" name="orderDate" value="<?= $orderDate ? htmlspecialchars($orderDate) : '' ?>"
                        class="border p-1 rounded text-[#122C4F]" placeholder="Filter by Date">
                </div>
                <button type="submit"
                    class="bg-[#122C4F] text-white px-3 py-1 rounded hover:bg-[#0e1f36]">Filter</button>
                <?php if ($userID || $orderDate): ?>
                    <button type="button" id="clearFilters"
                        class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Clear Filters</button>
                <?php endif; ?>
            </form>

            <!-- orders table -->
            <?php if (empty($orders)): ?>
                <div class="text-center py-4 text-[#122C4F]">
                    No orders found matching your filters.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full text-xs md:text-sm border border-gray-300 rounded-xl">
                        <thead class="bg-[#122C4F] text-[#FBF9E4] uppercase">
                            <tr>
                                <th class="px-2 py-2">Order ID</th>
                                <th class="px-2 py-2">User ID</th>
                                <th class="px-2 py-2">Product Name</th>
                                <th class="px-2 py-2">Qty</th>
                                <th class="px-2 py-2">Price</th>
                                <th class="px-2 py-2">Total</th>
                                <th class="px-2 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-[#122C4F]">
                            <?php
                            $lastOrderID = null;
                            foreach ($orders as $order):
                                $showOrderInfo = $order['orderID'] !== $lastOrderID;
                                $lastOrderID = $order['orderID'];
                                ?>
                                <tr>
                                    <td class="px-2 py-1"><?= $showOrderInfo ? $order['orderID'] : '' ?></td>
                                    <td class="px-2 py-1"><?= $showOrderInfo ? $order['userID'] : '' ?></td>
                                    <td class="px-2 py-1"><?= htmlspecialchars($order['productName']) ?></td>
                                    <td class="px-2 py-1"><?= $order['quantity'] ?></td>
                                    <td class="px-2 py-1">LKR <?= number_format($order['price'], 2) ?></td>
                                    <td class="px-2 py-1">
                                        <?= $showOrderInfo ? 'LKR ' . number_format($order['totalAmount'], 2) : '' ?>
                                    </td>
                                    <td class="px-2 py-1">
                                        <?= $showOrderInfo ? date('Y-m-d H:i', strtotime($order['orderDate'])) : '' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>

</body>


</html>