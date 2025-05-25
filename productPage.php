<?php
include_once 'functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$products = getLimitedTimeProducts();
$MensProducts = getMensProducts();
$WomensProducts = getWomensProducts();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>productsPage</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="./src/js/main.js" defer></script>
    <script src="./src/js/productPage.js" defer></script>
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

                <!-- Mobile nav -->
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
        <!-- Limited products Section -->
        <section class="w-full mt-20 mb-10">

            <!-- Desktop Version -->
            <div class="hidden sm:block w-full max-w-screen-2xl mx-auto">
                <div class="overflow-hidden rounded-xl transition-transform duration-300 ease-in-out hover:scale-102">
                    <img src="./src/otherPics/productPage-limited.png" alt="limited Product Banner"
                        class="w-full h-auto object-cover object-center transition-transform duration-300 ease-in-out">
                </div>
            </div>
            <!-- Mobile Version  -->
            <div class="sm:hidden w-full">
                <div class="overflow-hidden rounded-xl transition-transform duration-300 ease-in-out hover:scale-102">
                    <img src="./src/otherPics/productPage-limited-mobile.png" alt="Appreciation Banner"
                        class="w-full h-auto object-cover object-center transition-transform duration-300 ease-in-out">
                </div>
            </div>
        </section>


        <!-- Limited-Time Offers -->
        <section class="p-6 mt-10 sm:mt-16 md:mt-10">
            <h2 class="text-xl font-bold mb-9">Limited-Time Offers</h2>
            <div
                class="flex gap-6 overflow-x-auto sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-6 pb-4 scroll-smooth snap-x snap-mandatory">
                <?php foreach ($products as $product): ?>
                    <div
                        class="flex-shrink-0 w-64 md:w-85 bg-[#FBF9E4] rounded-xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden group snap-start text-[#122C4F] ">
                        <div class="relative h-48">
                            <img src="<?= htmlspecialchars($product['imagePath']) ?>"
                                class="w-full h-full object-contain p-6 transition-transform duration-500 group-hover:scale-105">
                            <div class="product-description absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-80 transition-opacity duration-300 flex items-center justify-center"
                                data-description-toggle>
                                <p class="text-white font-extrabold text-center text-md px-4">
                                    <?= htmlspecialchars($product['description']) ?>
                                </p>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold truncate"><?= htmlspecialchars($product['ProductName']) ?></h3>
                            <p class="text-xl font-bold mt-1">LKR <?= number_format($product['price'], 2) ?></p>
                            <form method="POST" name="addToCart" class="addToCart">
                                <input type="hidden" name="action" value="addToCart">
                                <input type="hidden" name="productID" value="<?= $product['productID'] ?>">
                                <button type="submit"
                                    class="bg-[#122C4F] text-[#FBF9E4] px-10 py-2 rounded hover:bg-[#0f203d] hover:shadow-lg hover:translate-y-[-2px] transition-all duration-300 w-full ">
                                    Add to Cart
                                </button>
                            </form>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>


        <!-- Mens Section -->
        <section class="w-full mt-20 mb-10">
            <!-- Desktop Version -->
            <div class="hidden sm:block w-full max-w-screen-2xl mx-auto">
                <div class="overflow-hidden rounded-xl transition-transform duration-300 ease-in-out hover:scale-102">
                    <img src="./src/otherPics/productPage-men.png" alt="Appreciation Banner"
                        class="w-full h-auto object-cover object-center transition-transform duration-300 ease-in-out">
                </div>
            </div>

            <!-- Mobile Version  -->
            <div class="sm:hidden w-full">
                <div class="overflow-hidden rounded-xl transition-transform duration-300 ease-in-out hover:scale-102">
                    <img src="./src/otherPics/productPage-men-mobile.png" alt="Appreciation Banner"
                        class="w-full h-auto object-cover object-center transition-transform duration-300 ease-in-out">
                </div>
            </div>
        </section>



        <!-- Men's Products -->
        <section class="p-6 mt-10 sm:mt-16 md:mt-16">
            <h2 class="text-xl font-bold mb-9">Men's Products</h2>
            <div
                class="flex gap-6 overflow-x-auto sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-6 pb-4 scroll-smooth snap-x snap-mandatory">
                <?php foreach ($MensProducts as $product): ?>
                    <div
                        class="flex-shrink-0 w-64 md:w-85 bg-[#FBF9E4] rounded-xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden group snap-start text-[#122C4F]">
                        <div class="relative h-48">
                            <img src="<?= htmlspecialchars($product['imagePath']) ?>"
                                class="w-full h-full object-contain p-6 transition-transform duration-500 group-hover:scale-105">
                            <div class="product-description absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-80 transition-opacity duration-300 flex items-center justify-center"
                                data-description-toggle>
                                <p class="text-white font-extrabold text-center text-md px-4">
                                    <?= htmlspecialchars($product['description']) ?>
                                </p>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold truncate"><?= htmlspecialchars($product['ProductName']) ?></h3>
                            <p class="text-xl font-bold mt-1">LKR <?= number_format($product['price'], 2) ?></p>
                            <form method="POST" name="addToCart" class="addToCart">
                                <input type="hidden" name="action" value="addToCart">
                                <input type="hidden" name="productID" value="<?= $product['productID'] ?>">
                                <button type="submit"
                                    class="bg-[#122C4F] text-[#FBF9E4] px-10 py-2 rounded hover:bg-[#0f203d] hover:shadow-lg hover:translate-y-[-2px] transition-all duration-300 w-full ">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>



        <!-- Womens Section -->
        <section class="w-full mt-20 mb-10">
            <!-- Desktop Version -->
            <div class="hidden sm:block w-full max-w-screen-2xl mx-auto">
                <div class="overflow-hidden rounded-xl transition-transform duration-300 ease-in-out hover:scale-102">
                    <img src="./src/otherPics/productPage-women.png" alt="Appreciation Banner"
                        class="w-full h-auto object-cover object-center transition-transform duration-300 ease-in-out">
                </div>
            </div>
            <!-- Mobile Version -->
            <div class="sm:hidden w-full">
                <div class="overflow-hidden rounded-xl transition-transform duration-300 ease-in-out hover:scale-102 ">
                    <img src="./src/otherPics/productPage-women-mobile.png" alt="Appreciation Banner"
                        class="w-full h-auto object-cover object-center transition-transform duration-300 ease-in-out">
                </div>
            </div>
        </section>


        <!-- Women's Products -->
        <section class="p-6 mt-10 sm:mt-16 md:mt-10 mb-25 md:mb-45">
            <h2 class="text-xl font-bold mb-9">Women's Products</h2>
            <div
                class="flex gap-6 overflow-x-auto sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-6 pb-4 scroll-smooth snap-x snap-mandatory">
                <?php foreach ($WomensProducts as $product): ?>
                    <div
                        class="flex-shrink-0 w-64 md:w-85 bg-[#FBF9E4] rounded-xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden group snap-start text-[#122C4F]">
                        <div class="relative h-48">
                            <img src="<?= htmlspecialchars($product['imagePath']) ?>"
                                class="w-full h-full object-contain p-6 transition-transform duration-500 group-hover:scale-105">
                            <div class="product-description absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-80 transition-opacity duration-300 flex items-center justify-center"
                                data-description-toggle>
                                <p class="text-white font-extrabold text-center text-md  px-4">
                                    <?= htmlspecialchars($product['description']) ?>
                                </p>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold truncate"><?= htmlspecialchars($product['ProductName']) ?></h3>
                            <p class="text-xl font-bold mt-1">LKR <?= number_format($product['price'], 2) ?></p>
                            <form method="POST" name="addToCart" class="addToCart">
                                <input type="hidden" name="action" value="addToCart">
                                <input type="hidden" name="productID" value="<?= $product['productID'] ?>">
                                <button type="submit"
                                    class="bg-[#122C4F] text-[#FBF9E4] px-10 py-2 rounded hover:bg-[#0f203d] hover:shadow-lg hover:translate-y-[-2px] transition-all duration-300 w-full ">
                                    Add to Cart
                                </button>
                            </form>
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

            <!-- Desktop footer  -->
            <div class="hidden md:grid grid-cols-4 gap-4 text-md font-semibold">
<!-- Contacts -->
                <div>
                    <h4 class="font-semibold mb-2">Contact Us</h4>
                    <p>Phone: 110-000-0001</p>
                    <p>Email: ScentMemoir@gmail.com</p>
                    <p>Address: Union Pl, Colombo</p>
                </div>
                <!-- quick links -->
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