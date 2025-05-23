<?php
include_once 'functions.php';

$products = getLimitedTimeProducts();

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

    <section class="p-6 mt-10 sm:mt-16 md:mt-28">
        <h2 class="text-xl font-bold mb-9">Advertisements</h2>
        <div class="flex space-x-4 overflow-x-auto snap-x snap-mandatory scrollbar-hide">
            <!-- Ad Cards -->
            <div class="min-w-[200px] rounded-2xl overflow-hidden flex-shrink-0 snap-center ">
                <img src="./adsPictures/Calvin Klein Eternity for Men.png" alt="" class="w-full h-60 object-cover">
            </div>
            <div class="min-w-[200px] rounded-2xl overflow-hidden flex-shrink-0 snap-center ">
                <img src=" ./adsPictures/Calvin Klein CK One.png" alt="" class="w-full h-60 object-cover">
            </div>
            <div class="min-w-[200px] rounded-2xl overflow-hidden flex-shrink-0 snap-center ">
                <img src="./adsPictures/Chanel Coco Mademoiselle.png" alt="" class="w-full h-60 object-cover">
            </div>
            <div class="min-w-[200px] rounded-2xl overflow-hidden flex-shrink-0 snap-center ">
                <img src="./adsPictures/Dior J’adore.png" alt="" class="w-full h-60 object-cover">
            </div>
            <div class="min-w-[200px] rounded-2xl overflow-hidden flex-shrink-0 snap-center ">
                <img src="./adsPictures/Yves Saint Laurent Black Opium.png" alt="" class="w-full h-60 object-cover">
            </div>
        </div>
    </section>


    <!---Lmited products--->
    <section class="p-6 mt-20">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold">Limited-Time Offers</h2>
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
                    <div
                        class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-80 transition-opacity duration-300 flex items-center justify-center">
                        <p class="text-white font-extrabold text-center text-sm px-4">
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

    <section class="p-6 w-full">
        <h2 class="text-2xl font-bold mb-6 text-center">Available Brands</h2>
        <div class="overflow-x-auto snap-x snap-mandatory scrollbar-hide">
            <div class="flex justify-center space-x-6 px-6">
                <div class="w-52 h-52 rounded-full overflow-hidden flex-shrink-0 snap-center bg-[#FBF9E4]">
                    <img src="./availableBrands/Calvin Klein.png" alt="Calvin Klein" class="w-full h-full object-cover">
                </div>
                <div class="w-52 h-52 rounded-full overflow-hidden flex-shrink-0 snap-center bg-[#F2F0DE]">
                    <img src="./availableBrands/CHANEL.png" alt="CHANEL" class="w-full h-full object-cover">
                </div>
                <div class="w-52 h-52 rounded-full overflow-hidden flex-shrink-0 snap-center bg-[#F2F0DE]">
                    <img src="./availableBrands/YSL.png" alt="YSL" class="w-full h-full object-cover">
                </div>
                <div class="w-52 h-52 rounded-full overflow-hidden flex-shrink-0 snap-center bg-[#F2F0DE]">
                    <img src="./availableBrands/DIOR.png" alt="DIOR" class="w-full h-full object-cover">
                </div>
                <div class="w-52 h-52 rounded-full overflow-hidden flex-shrink-0 snap-center bg-[#F2F0DE]">
                    <img src="./availableBrands/AMOUAGE.png" alt="AMOUAGE" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
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