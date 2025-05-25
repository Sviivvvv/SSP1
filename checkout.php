<?php
require_once 'functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// check if user logged in
if (!isset($_SESSION['userID'])) {
    echo "<script>
    alert('You must be logged in to checkout.');
    window.location.href = '/luxuryperfumestore/login';
    </script>";
    exit;
}


$userID = $_SESSION['userID'];


// Get the users cart items and subtotal
$cartItems = getCartItems($userID);
$subtotal = getCartSubtotal($userID);



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="./src/js/main.js" defer></script>
    <script src="./src/js/checkout.js" defer></script>
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

            <!-- nav bar -->
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
        <!-- checkout section -->

        <section class="p-4 sm:p-6 mt-20 w-full max-w-screen-xl mx-auto text-[#FBF9E4]">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Order Summary -->
                <div class="lg:w-1/3 mt-3 font-semibold md:mr-40 ">
                    <h3 class="text-2xl sm:text-xl xl:text-3xl font-bold mb-4">
                        Cart Summary
                    </h3>
                    <div class="p-4 bg-[#FBF9E4] text-[#122C4F] rounded-lg border-l-4 shadow-lg w-full 
                lg:sticky lg:top-4 lg:w-[450px] xl:w-[550px]">
                        <!-- Product List -->
                        <div class="space-y-3 mb-4">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="flex justify-between text-sm sm:text-base lg:text-lg xl:text-xl">
                                    <span
                                        class="block w-2/3 break-words"><?= htmlspecialchars($item['ProductName']) ?></span>
                                    <span
                                        class="block w-1/3 text-right"><?= number_format($item['price'] * $item['quantity'], 2) ?>
                                        LKR</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Subtotal -->
                        <div
                            class="flex justify-between items-center py-2 border-t mt-2 font-bold text-xs sm:text-sm lg:text-base xl:text-lg">
                            <span>Subtotal</span>
                            <span><?= number_format($subtotal, 2) ?> LKR</span>
                        </div>
                    </div>
                </div>

                <!--  Billing and Payment section -->
                <form id="checkoutForm" method="POST" action="/luxuryperfumestore/checkout"
                    class="flex-1 flex flex-col gap-8">
                    <!-- Billing Information -->
                    <div class="p-6">
                        <h2 class="text-2xl mb-4">Billing Information</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <input type="text" name="first_name" placeholder="First Name" required
                                class="bg-[#FBF9E4] text-[#122C4F] placeholder-[#122C4F] p-2 font-semibold">
                            <input type="text" name="last_name" placeholder="Last Name" required
                                class="bg-[#FBF9E4] text-[#122C4F] placeholder-[#122C4F] p-2 font-semibold">
                        </div>
                        <input type="email" name="email" placeholder="Email Address" required
                            class="bg-[#FBF9E4] text-[#122C4F] placeholder-[#122C4F] p-2 mb-4 w-full font-semibold">
                        <input type="tel" name="phone" placeholder="Phone Number" required
                            class="bg-[#FBF9E4] text-[#122C4F] placeholder-[#122C4F] p-2 mb-4 w-full font-semibold">
                        <input type="text" name="address" placeholder="Street Address" required
                            class="bg-[#FBF9E4] text-[#122C4F] placeholder-[#122C4F] p-2 mb-4 w-full font-semibold">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <input type="text" name="city" placeholder="Town/City" required
                                class="bg-[#FBF9E4] text-[#122C4F] placeholder-[#122C4F] p-2 font-semibold">
                            <input type="text" name="zip" placeholder="Postal code/Zip" required
                                class="bg-[#FBF9E4] text-[#122C4F] placeholder-[#122C4F] p-2 font-semibold">
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="p-6">
                        <h2 class="text-2xl mb-4">Payment Information</h2>
                        <div class="flex items-center gap-6 mb-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="payment_method" value="card" checked class="accent-[#122C4F]">
                                <span class="text-sm">Credit/Debit Card</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" class="accent-[#122C4F]">
                                <span class="text-sm">Cash on Delivery</span>
                            </label>
                        </div>

                        <!-- Card Fields (shown by default) -->
                        <div id="cardFields">
                            <div class="mb-4">
                                <input type="text" name="card_name" placeholder="Cardholder's Name" required
                                    class="bg-[#FBF9E4] text-[#122C4F] placeholder-[#122C4F] p-2 font-semibold w-full">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="card_number" placeholder="Card Number" required
                                    class="bg-[#FBF9E4] text-[#122C4F] placeholder-[#122C4F] p-2 font-semibold w-full">
                            </div>
                            <div class="flex gap-4 mb-4">
                                <select name="exp_month" required
                                    class="bg-[#FBF9E4] text-[#122C4F] p-2 font-semibold flex-1 min-w-0 w-full">
                                    <option value="">Month</option>
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?= $m ?>"><?= str_pad($m, 2, '0', STR_PAD_LEFT) ?></option>
                                    <?php endfor; ?>
                                </select>
                                <select name="exp_year" required
                                    class="bg-[#FBF9E4] text-[#122C4F] p-2 font-semibold flex-1 min-w-0 w-full">
                                    <option value="">Year</option>
                                    <?php for ($y = date('Y'); $y <= date('Y') + 10; $y++): ?>
                                        <option value="<?= $y ?>"><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                                <input type="text" name="cvv" placeholder="CVV" required
                                    class="bg-[#FBF9E4] text-[#122C4F] p-2 font-semibold flex-1 min-w-0 w-full">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#FBF9E4] text-[#122C4F] font-semibold p-2 hover:bg-[#FBF9E4]/50 transition">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>


    <!-- footer -->
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

            <!-- Desktop footer  -->
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