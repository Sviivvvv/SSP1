<?php
require_once 'functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// checking if user is logged in

if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('You must be logged in to view your order history.');
        window.location.href = '/luxuryperfumestore/login';
    </script>";
    exit;
}
$userID = $_SESSION['user_id'];
$orderHistory = getOrderHistory($userID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
    <header class="w-full px-8 py-4 relative">
        <!-- Logo -->
        <div class="absolute top-4 left-4">
            <p class="text-xl sm:text-3xl md:text-4xl font-bold">Scent Memoir</p>
        </div>

        <!-- Navigation -->
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
                <a href="logout.php" class="text-md font-bold hover:underline transition-colors duration-200">Logout</a>
            </div>

            <!-- Mobile Button -->
            <div class="md:hidden flex justify-end">
                <button id="burgerButton" class="focus:outline-none relative group">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-br from-[#FBF9E4]/20 to-[#FBF9E4]/40 
                        scale-0 group-hover:scale-100 group-active:scale-110 opacity-0 
                        group-hover:opacity-100 group-active:opacity-100 transition-all duration-1000-m-3 blur-[2px]">
                    </div>
                    <img src="./otherPics/perfumehamburger.png" alt="Menu"
                        class="relative w-13 h-13 transition-transform duration-300 group-hover:scale-110 z-10">
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden overflow-hidden">
                <div class="flex flex-col items-end space-y-3 mt-4 px-4">
                    <a href="/luxuryperfumestore/index"
                        class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300">Home</a>
                    <a href="/luxuryperfumestore/productPage"
                        class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-75">Products</a>
                    <a href="/luxuryperfumestore/cartPage"
                        class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-100">Cart</a>
                    <a href="/luxuryperfumestore/subscriptionPage"
                        class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-150">Subscription</a>
                    <a href="logout.php"
                        class="opacity-0 transform translate-y-3 text-md font-bold hover:underline transition-all duration-300 delay-200">Logout</a>
                </div>
            </div>
        </nav>

        <script>
            const burgerButton = document.getElementById('burgerButton');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuLinks = mobileMenu.querySelectorAll('a');

            burgerButton.addEventListener('click', () => {
                if (mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.remove('hidden');
                    setTimeout(() => {
                        menuLinks.forEach(link => {
                            link.classList.remove('opacity-0', 'translate-y-3');
                        });
                    }, 50);
                } else {
                    menuLinks.forEach(link => {
                        link.classList.add('opacity-0', 'translate-y-3');
                    });
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);
                }
            });
        </script>
    </header>


    <section class="p-4 sm:p-6 mt-20 w-full max-w-screen-2xl mx-auto bg-[#122C4F] text-[#FBF9E4]">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6 text-center">Order History</h1>

        <?php if (empty($orderHistory)): ?>
            <p class="text-center text-sm sm:text-lg lg:text-xl">You have no previous orders.</p>
            <div class="text-center mt-4">
                <a href="/luxuryperfumestore/productPage"
                    class="inline-block px-4 py-2 border font-bold bg-[#FBF9E4] text-[#122C4F] rounded hover:bg-[#f1edd9] text-sm sm:text-base transition hover:shadow-lg hover:-translate-y-0.5">
                    Start Shopping
                </a>
            </div>
        <?php else: ?>

            <div class="mt-6 w-full mx-auto max-w-6xl">
                <div class="rounded-xl overflow-hidden border border-[#FBF9E4]/30">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[550px] md:min-w-0 border-collapse text-xs sm:text-sm">
                            <thead>
                                <tr class="bg-[#FBF9E4] text-[#122C4F]">
                                    <th class="text-center py-3 px-3 border border-[#FBF9E4]/30">Order ID</th>
                                    <th class="text-center py-3 px-3 border border-[#FBF9E4]/30">Product Name</th>
                                    <th class="text-center py-3 px-3 border border-[#FBF9E4]/30">Quantity</th>
                                    <th class="text-center py-3 px-3 border border-[#FBF9E4]/30">Price (LKR)</th>
                                    <th class="text-center py-3 px-3 border border-[#FBF9E4]/30">Total (LKR)</th>
                                    <th class="text-center py-3 px-3 border border-[#FBF9E4]/30">Order Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderHistory as $order): ?>
                                    <tr class="bg-[#122C4F] hover:bg-[#1a3a6a] transition-colors">
                                        <td class="py-3 px-3 text-center border border-[#FBF9E4]/30">
                                            <?= htmlspecialchars($order['orderID']) ?>
                                        </td>
                                        <td class="py-3 px-3 text-center border border-[#FBF9E4]/30">
                                            <?= htmlspecialchars($order['productName']) ?>
                                        </td>
                                        <td class="py-3 px-3 text-center border border-[#FBF9E4]/30">
                                            <?= htmlspecialchars($order['quantity']) ?>
                                        </td>
                                        <td class="py-3 px-3 text-center border border-[#FBF9E4]/30">
                                            <?= number_format($order['price'], 2) ?>
                                        </td>
                                        <td class="py-3 px-3 text-center border border-[#FBF9E4]/30 font-semibold">
                                            <?= number_format($order['totalPrice'], 2) ?>
                                        </td>
                                        <td class="py-3 px-3 text-center border border-[#FBF9E4]/30">
                                            <?= htmlspecialchars($order['orderDate']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        <?php endif; ?>
    </section>




    <footer class="bg-[#122C4F] border-t mt-8">
        <div class="container mx-auto px-4 py-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-md font-semibold">
            <div>
                <h4 class="font-semibold mb-2">Contact Us</h4>
                <p>Number</p>
                <p>Email</p>
                <p>Address</p>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Quick Links</h4>
                <a href="index.php" class="block hover:underline">Home</a>
                <a href="products.php" class="block hover:underline">Products</a>
                <a href="cart.php" class="block hover:underline">Cart</a>
                <a href="subscription.php" class="block hover:underline">Subscription</a>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Socials</h4>
                <div class="flex space-x-2">
                    <div class="w-6 h-6 bg-[#081F5C] rounded"></div>
                    <div class="w-6 h-6 bg-[#081F5C] rounded"></div>
                    <div class="w-6 h-6 bg-[#081F5C] rounded"></div>
                </div>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Newsletter</h4>
                <input type="email" placeholder="Your email" class="w-full border px-2 py-1 rounded mb-2 text-sm">
                <button
                    class="w-full bg-[#FBF9E4] text-[#122C4F] text-sm py-1 rounded hover:bg-[#FBF9E4]/90">Subscribe</button>
            </div>
        </div>
    </footer>
</body>


</html>