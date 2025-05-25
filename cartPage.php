<?php
include_once 'functions.php';


$products = getLimitedTimeProducts();
$cartItems = [];
$subtotal = 0;

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $cartItems = getCartItems($userID);
    $subtotal = getCartSubtotal($userID);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cartPage</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="./src/js/main.js" defer></script>
    <script src="./src/js/cartPage.js" defer></script>
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

            <!-- Nav Bar -->
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

                <!-- Mobile Nav -->
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
        <!-- Cart Section -->
        <section class="p-4 sm:p-6 mt-20 w-full max-w-screen-2xl mx-auto bg-[#122C4F] text-[#FBF9E4]">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6">Shopping Cart</h1>

            <?php if (empty($cartItems)): ?>
                <p class="text-sm sm:text-lg lg:text-xl">Your cart is empty.</p>
                <a href="/luxuryperfumestore/productPage"
                    class="text-[#FBF9E4] hover:underline text-sm sm:text-base lg:text-lg">Continue
                    Shopping?</a>
                <p>Or</p>
                <a href="/luxuryperfumestore/orderHistory"
                    class="text-[#FBF9E4] hover:underline text-sm sm:text-base lg:text-lg">Order History?</a>
            <?php else: ?>

                <!-- Cart Table -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                    <div class="lg:col-span-2 overflow-x-auto order-1 lg:order-1">
                        <table class="w-full border-collapse text-sm sm:text-base lg:text-lg xl:text-xl">
                            <thead>
                                <tr class="border-b border-[#FBF9E4]">
                                    <th class="text-center py-3 px-4">Product</th>
                                    <th class="text-center py-3 px-4">Price (LKR)</th>
                                    <th class="text-center py-3 px-4">Quantity</th>
                                    <th class="text-center py-3 px-4">Total (LKR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr class="border-b border-[#FBF9E4]/40" data-product-id="<?= $item['productID'] ?>">
                                        <td class="py-4 px-4 text-left align-right">
                                            <div class="flex items-center gap-3">
                                                <?php if ($item['isSubscription'] == 0): ?>
                                                    <div class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0">
                                                        <img src="<?= htmlspecialchars($item['imagePath']) ?>"
                                                            class="w-full h-full object-cover rounded">
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <h2 class="font-semibold text-sm sm:text-base lg:text-lg xl:text-xl">
                                                        <?= htmlspecialchars($item['ProductName']) ?>
                                                    </h2>
                                                    <button type="button"
                                                        class="remove-item-btn text-red-400 hover:underline text-xs sm:text-sm lg:text-base xl:text-lg">Remove</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-center align-middle price">
                                            <?= number_format($item['price'], 2) ?>
                                        </td>

                                        <td class="py-4 px-4 text-center align-middle">
                                            <?php if ($item['isSubscription'] == 0): ?>
                                                <div class="flex items-center justify-center gap-2">
                                                    <button type="button"
                                                        class="quantity-decrease px-3 py-2 bg-[#FBF9E4] text-[#122C4F] hover:bg-[#FBF9E4]/80 border-l rounded-l">-</button>

                                                    <span
                                                        class="quantity-display px-3 py-2 border border-[#FBF9E4] bg-[#122C4F] text-[#FBF9E4]">
                                                        <?= $item['quantity'] ?>
                                                    </span>

                                                    <button type="button"
                                                        class="quantity-increase px-3 py-2 bg-[#FBF9E4] text-[#122C4F] hover:bg-[#FBF9E4]/80 border-r rounded-r">+</button>
                                                </div>
                                            <?php else: ?>
                                                <div class="flex justify-center">
                                                    <span
                                                        class="quantity-display px-3 py-2 border border-[#FBF9E4] bg-[#122C4F] text-[#FBF9E4]">
                                                        <?= $item['quantity'] ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <td
                                            class="py-4 px-4 text-center align-middle font-bold text-sm sm:text-base lg:text-lg xl:text-xl total">
                                            <?= number_format($item['price'] * $item['quantity'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!--cart summary -->
                    <div class="order-2 lg:order-2 mt-8 sm:mt-0">
                        <h3 class="text-lg sm:text-xl lg:text-2xl xl:text-3xl font-bold mb-4">Cart Summary</h3>
                        <div
                            class="p-4 bg-[#FBF9E4] text-[#122C4F] rounded-lg sticky lg:top-4 border-l-4 shadow-lg w-full lg:w-[450px] xl:w-[550px]">
                            <div class="space-y-3 mb-4">
                                <?php foreach ($cartItems as $item): ?>
                                    <div class="flex justify-between text-sm sm:text-base lg:text-lg xl:text-xl">
                                        <span><?= htmlspecialchars($item['ProductName']) ?></span>
                                        <span><?= number_format($item['price'] * $item['quantity'], 2) ?> LKR</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div
                                class="flex justify-between py-2 border-t mt-2 font-bold text-xs sm:text-sm lg:text-base xl:text-lg">
                                <span>Subtotal</span>
                                <span><?= number_format($subtotal, 2) ?> LKR</span>
                            </div>
                            <div class="flex gap-3 mt-5">
                                <a href="/luxuryperfumestore/orderHistory"
                                    class="flex-1 px-4 py-2 border font-bold bg-[#122C4F] text-[#FBF9E4] rounded hover:bg-[#0f203d] text-center hover:shadow-xl hover:translate-y-[-2px] transition text-xs sm:text-sm lg:text-base xl:text-lg">Order
                                    History</a>
                                <a href="/luxuryperfumestore/checkout"
                                    class="flex-1 px-4 py-2 font-bold bg-[#122C4F] text-[#FBF9E4] rounded hover:bg-[#0f203d] text-center hover:shadow-xl hover:translate-y-[-2px] transition text-xs sm:text-sm lg:text-base xl:text-lg">Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </section>



        <!-- Limited products -->
        <section class="p-6 mt-20">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold mb-9">Limited-Time Offers</h2>
                <a href="/luxuryperfumestore/productPage"
                    class="hover:text-[DAD3C9] font-bold transition-colors duration-200">
                    View More &rarr;
                </a>
            </div>
            <div class="flex gap-6 overflow-x-auto pb-4 text-[#122C4F] scroll-smooth snap-x snap-mandatory">
                <?php foreach ($products as $product): ?>
                    <div
                        class="flex-shrink-0 w-64 bg-[#FBF9E4] rounded-xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden group snap-start">
                        <div class="relative h-48">
                            <img src="<?= htmlspecialchars($product['imagePath']) ?>"
                                class="w-full h-full object-contain p-6 bg-[#FBF9E4] transition-transform duration-500 group-hover:scale-105">
                            <div class="product-description absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-80 transition-opacity duration-300 flex items-center justify-center"
                                data-description-toggle>
                                <p class="text-white font-extrabold text-center text-md px-4">
                                    <?= htmlspecialchars($product['description']) ?>
                                </p>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold truncate">
                                <?= htmlspecialchars($product['ProductName']) ?>
                            </h3>
                            <p class="text-xl font-bold mt-1">
                                LKR
                                <?= number_format($product['price'], 2) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
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