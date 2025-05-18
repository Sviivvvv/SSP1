<?php
include_once 'functions.php';


$subscriptionProducts = getSubscriptionProducts();

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

            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.addToCart').forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();

                        const formData = new FormData(form);

                        fetch('route.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    alert(data.message);
                                } else {
                                    alert(data.message || 'Failed to add to cart.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    });
                });
            });


        </script>
    </header>
    <section class="p-6 mt-10 sm:mt-16 md:mt-28">
        <h2 class="text-xl font-bold mb-9">
            Subscriptions
        </h2>

        <div class="space-y-8 sm:space-y-10 mx-auto">
            <?php foreach ($subscriptionProducts as $product): ?>
                <div class="flex flex-col items-center">
                    <!-- Card container -->
                    <div
                        class="bg-[#FBF9E4] text-[#122C4F] p-5 sm:p-6 rounded-lg shadow-sm w-full max-w-[300px] sm:max-w-[600px]">
                        <!-- Image container -->
                        <div
                            class="mb-4 mx-auto w-24 h-24 sm:w-32 sm:h-32 rounded-lg overflow-hidden border-2 border-[#122C4F]">
                            <img src="<?= htmlspecialchars($product['imagePath'] ?? 'placeholder.jpg'); ?>"
                                alt="<?= htmlspecialchars($product['ProductName']); ?>" class="w-full h-full object-cover">
                        </div>

                        <!-- Text content -->
                        <h3 class="text-sm sm:text-base font-semibold mb-3 text-center leading-tight">
                            <?= htmlspecialchars($product['ProductName']); ?>
                        </h3>
                        <p class="text-xs sm:text-sm font-medium text-[#0f203d] mb-4 text-center">
                            Rs.<?= number_format($product['price'], 0); ?>/Month
                        </p>
                        <p class="text-xs text-gray-600 mb-6 text-center leading-snug">
                            <?= htmlspecialchars($product['description']); ?>
                        </p>

                        <!-- Centered button with padding -->
                        <form action="route.php" method="POST" class="mt-6 mb-2 w-full flex justify-center addToCart">
                            <input type="hidden" name="action" value="addToCart" />
                            <input type="hidden" name="productID" value="<?= $product['productID']; ?>" />
                            <button type="submit"
                                class="bg-[#122C4F] text-[#FBF9E4] px-10 py-2 rounded hover:bg-[#0f203d] hover:shadow-lg hover:translate-y-[-2px] transition-all duration-300">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>



    <footer class=" bg-[#122C4F] border-t mt-8">
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