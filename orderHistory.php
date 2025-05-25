<?php
require_once 'functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// checking if user is logged in

if (!isset($_SESSION['userID'])) {
    echo "<script>
        alert('You must be logged in to view your order history.');
        window.location.href = '/luxuryperfumestore/login';
    </script>";
    exit;
}
$userID = $_SESSION['userID'];
$groupedOrders = getGroupedOrderHistory($userID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>orderHistory</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="./src/js/main.js" defer></script>
    <style>
        body {
            font-family: 'Garamond', srif;
        }

        div::-webkit-scrollbar {
            display: none;
        }
    </style>


</head>

<body class="bg-[#122C4F] text-[#FBF9E4] min-h-screen">
    <header>
        <div class="w-full px-8 py-4 relative">
            <!-- Logo -->
            <div class="absolute mt-3 top-4 left-6">
                <p class="text-xl sm:text-3xl md:text-4xl font-bold">Scent Memoir</p>
            </div>

            <!-- Nav bar -->
            <nav class="mt-20">
                <div class="hidden md:flex justify-end space-x-14">
                    <a href="/luxuryperfumestore/index"
                        class="text-md font-bold hover:underline transition-colors duration-200">Home</a>
                    <a href="/luxuryperfumestore/productPage"
                        class="text-md font-bold hover:underline transition-colors duration-200">Products</a>
                    <a href="/luxuryperfumestore/cartPage"
                        class="text-md font-bold hover:underline transition-colors duration-200">Cart</a>
                    <a href="/luxuryperfumestore/subscriptionPage"
                        class="text-md font-bold hover:underline transition-colors duration-200">Subscription</a>
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

                <!-- Mobile Nav-->
                <div id="mobileNav" class="hidden md:hidden overflow-hidden">
                    <div class="flex flex-col items-end space-y-3 mt-4 px-4">
                        <a href="/luxuryperfumestore/index"
                            class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300">Home</a>
                        <a href="/luxuryperfumestore/productPage"
                            class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-75">Products</a>
                        <a href="/luxuryperfumestore/cartPage"
                            class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-100">Cart</a>
                        <a href="/luxuryperfumestore/subscriptionPage"
                            class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-150">Subscription</a>

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
        </div>
    </header>

    <main>
        <!-- Order history section -->
        <section class="p-6 sm:p-8 mt-24 w-full max-w-screen-2xl mx-auto mb-25 md:mb-45 bg-[#122C4F] text-[#FBF9E4]">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-8 text-center">Order History</h1>

            <?php if (empty($groupedOrders)): ?>
                <p class="text-center text-lg sm:text-xl mb-6 opacity-90">You have no previous orders.</p>
                <div class="text-center">
                    <a href="/luxuryperfumestore/productPage"
                        class="inline-block px-6 py-3 font-semibold bg-[#FBF9E4] text-[#122C4F] rounded-full shadow-md hover:bg-[#f1edd9] transition duration-200 text-base sm:text-lg">
                        Start Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="mt-10 w-full mx-auto max-w-6xl space-y-10">
                    <?php foreach ($groupedOrders as $orderID => $orderGroup): ?>
                        <div
                            class="rounded-2xl overflow-hidden border border-[#FBF9E4]/20 shadow-lg hover:shadow-xl transition-shadow duration-300">

                            <!-- Order Header -->
                            <div
                                class="bg-[#FBF9E4] text-[#122C4F] p-6 flex flex-col sm:flex-row justify-between sm:items-center">
                                <div>
                                    <h3 class="font-bold text-xl tracking-wide">Order #<?= $orderID ?></h3>
                                    <p class="text-sm sm:text-base mt-1 opacity-80">Date: <?= $orderGroup['orderDate'] ?></p>
                                </div>
                                <div class="mt-4 sm:mt-0">
                                    <p class="font-bold text-xl">Total: <?= number_format($orderGroup['orderTotal'], 2) ?> LKR
                                    </p>
                                </div>
                            </div>

                            <!-- Order Items Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[550px] text-sm sm:text-base text-left border-collapse">
                                    <thead>
                                        <tr class="bg-[#1a3a6a] text-[#FBF9E4] uppercase text-xs sm:text-sm tracking-wider">
                                            <th class="py-4 px-4 border-b border-[#FBF9E4]/30">Product</th>
                                            <th class="py-4 px-4 text-center border-b border-[#FBF9E4]/30">Quantity</th>
                                            <th class="py-4 px-4 text-center border-b border-[#FBF9E4]/30">Unit Price</th>
                                            <th class="py-4 px-4 text-center border-b border-[#FBF9E4]/30">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderGroup['items'] as $item): ?>
                                            <tr class="hover:bg-[#1a3a6a] transition-colors border-t border-[#FBF9E4]/10">
                                                <td class="py-4 px-4 border-b border-[#FBF9E4]/15">
                                                    <?= htmlspecialchars($item['productName']) ?>
                                                </td>
                                                <td class="py-4 px-4 text-center border-b border-[#FBF9E4]/15">
                                                    <?= htmlspecialchars($item['quantity']) ?>
                                                </td>
                                                <td class="py-4 px-4 text-center border-b border-[#FBF9E4]/15">
                                                    <?= number_format($item['price'], 2) ?>
                                                </td>
                                                <td class="py-4 px-4 text-center font-semibold border-b border-[#FBF9E4]/15">
                                                    <?= number_format($item['totalPrice'], 2) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!--footer-->

    <footer class="bg-[#122C4F] border-t mt-8">
        <div class="container mx-auto px-4 py-6">

            <!-- Mobile footer -->
            <div class="md:hidden flex flex-col items-center space-y-8 text-center">
                <!-- Contact -->
                <div>
                    <h4 class="font-semibold mb-3 text-lg">Contact Us</h4>
                    <div class="space-y-1">
                        <p>Phone: 110-000-0001</p>
                        <p>Email: ScentMemoir@gmail.com</p>
                        <p>Address: Union Pl, Colombo</p>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold mb-3 text-lg">Quick Links</h4>
                    <div class="space-y-2">
                        <a href="/luxuryperfumestore/index"
                            class="block text-sm hover:underline transition-colors duration-200">Home</a>
                        <a href="/luxuryperfumestore/productPage"
                            class="block text-sm hover:underline transition-colors duration-200">Products</a>
                        <a href="/luxuryperfumestore/cartPage"
                            class="block text-sm hover:underline transition-colors duration-200">Cart</a>
                        <a href="/luxuryperfumestore/subscriptionPage"
                            class="block text-sm hover:underline transition-colors duration-200">Subscription</a>
                    </div>
                </div>

                <!-- Socials -->
                <div>
                    <h4 class="font-semibold mb-3 text-lg">Socials</h4>
                    <div class="flex justify-center space-x-6">
                        <a href="https://instagram.com" target="_blank" class="hover:opacity-80 transition-opacity">
                            <img src="./src/otherPics/instaIcon.png" alt="Instagram" class="w-9 h-9">
                        </a>
                        <a href="https://facebook.com" target="_blank" class="hover:opacity-80 transition-opacity">
                            <img src="./src/otherPics/FbIcon.png" alt="Facebook" class="w-9 h-9">
                        </a>
                        <a href="https://tiktok.com" target="_blank" class="hover:opacity-80 transition-opacity">
                            <img src="./src/otherPics/TiktokIcon.png" alt="TikTok" class="w-9 h-9">
                        </a>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="w-full max-w-xs">
                    <h4 class="font-semibold mb-3 text-lg">Newsletter</h4>
                    <input type="email" placeholder="Your email" class="w-full border px-3 py-2 rounded mb-3 text-sm">
                    <button
                        class="w-full bg-[#FBF9E4] text-[#122C4F] text-sm py-2 rounded hover:bg-[#FBF9E4]/90 transition-colors">
                        Subscribe
                    </button>
                </div>
            </div>

            <!-- Desktop footer -->
            <div class="hidden md:grid grid-cols-4 gap-4 text-md font-semibold">
                <!-- Contacts -->
                <div>
                    <h4 class="font-semibold mb-2">Contact Us</h4>
                    <p>Phone: 110-000-0001</p>
                    <p>Email: ScentMemoir@gmail.com</p>
                    <p>Address: Union Pl, Colombo</p>
                </div>
                <!-- Quick links -->
                <div>
                    <h4 class="font-semibold mb-2">Quick Links</h4>
                    <a href="/luxuryperfumestore/index"
                        class="block hover:underline transition-colors duration-200">Home</a>
                    <a href="/luxuryperfumestore/productPage"
                        class="block hover:underline transition-colors duration-200">Products</a>
                    <a href="/luxuryperfumestore/cartPage"
                        class="block hover:underline transition-colors duration-200">Cart</a>
                    <a href="/luxuryperfumestore/subscriptionPage"
                        class="block hover:underline transition-colors duration-200">Subscription</a>
                </div>
                <!-- Socials -->
                <div>
                    <h4 class="font-semibold mb-2">Socials</h4>
                    <div class="flex space-x-4">
                        <a href="https://instagram.com" target="_blank" class="hover:opacity-80 transition-opacity">
                            <img src="./src/otherPics/instaIcon.png" alt="Instagram" class="w-8 h-8">
                        </a>
                        <a href="https://facebook.com" target="_blank" class="hover:opacity-80 transition-opacity">
                            <img src="./src/otherPics/FbIcon.png" alt="Facebook" class="w-8 h-8">
                        </a>
                        <a href="https://tiktok.com" target="_blank" class="hover:opacity-80 transition-opacity">
                            <img src="./src/otherPics/TiktokIcon.png" alt="TikTok" class="w-8 h-8">
                        </a>
                    </div>
                </div>
                <!-- News letter -->
                <div>
                    <h4 class="font-semibold mb-2">Newsletter</h4>
                    <input type="email" placeholder="Your email" class="w-full border px-2 py-1 rounded mb-2 text-sm">
                    <button
                        class="w-full bg-[#FBF9E4] text-[#122C4F] text-sm py-1 rounded hover:bg-[#FBF9E4]/90 transition-colors">
                        Subscribe
                    </button>
                </div>
            </div>
        </div>
    </footer>
</body>


</html>