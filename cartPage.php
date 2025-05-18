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
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // Remove Item
                document.querySelectorAll('.remove-item-btn').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();

                        const productID = this.closest('tr').dataset.productId; // Get the productID from the tr's data attribute

                        const formData = new FormData();
                        formData.append('action', 'removeCart');
                        formData.append('productID', productID); // Add the productID to the request

                        fetch('route.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(res => res.json())
                            .then(data => {
                                alert(data.message || 'Item removed.');
                                if (data.success) {
                                    location.reload();
                                }
                            })
                            .catch(err => {
                                console.error('Error:', err);
                                alert('An error occurred while removing item.');
                            });
                    });
                });

            });

        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Handle increase and decrease buttons
                document.querySelectorAll('.quantity-increase, .quantity-decrease').forEach(button => {
                    button.addEventListener('click', function () {
                        const row = button.closest('tr'); // Get the row
                        const productID = row.dataset.productId; // Get the product ID
                        let quantity = parseInt(row.querySelector('.quantity-display').textContent); // Get the current quantity

                        // Update quantity based on button clicked
                        if (button.classList.contains('quantity-increase')) {
                            quantity++;
                        } else if (button.classList.contains('quantity-decrease') && quantity > 1) {
                            quantity--;
                        }

                        updateQuantity(productID, quantity, row);
                    });
                });

                function updateQuantity(productID, quantity, row) {
                    const formData = new FormData();
                    formData.append('action', 'updateCart');
                    formData.append('productID', productID);
                    formData.append('quantity', quantity);

                    fetch('route.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                row.querySelector('.quantity-display').textContent = quantity;
                                const price = parseFloat(row.querySelector('.price').textContent.replace(/,/g, ''));
                                row.querySelector('.total').textContent = (price * quantity).toFixed(2);
                                location.reload();
                            } else {
                                alert(data.message || 'Failed to update quantity.');
                            }
                        });
                }
            });
        </script>
    </header>

    <section class="p-4 sm:p-6 mt-20 w-full max-w-screen-2xl mx-auto bg-[#122C4F] text-[#FBF9E4]">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6">Shopping Cart</h1>

        <?php if (empty($cartItems)): ?>
            <p class="text-sm sm:text-lg lg:text-xl">Your cart is empty.</p>
            <a href="productPage.php" class="text-[#FBF9E4] hover:underline text-sm sm:text-base lg:text-lg">Continue
                Shopping</a>
        <?php else: ?>

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
                                    <td class="py-4 px-4 text-center align-middle">
                                        <div class="flex items-center gap-3">
                                            <div class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0">
                                                <img src="<?= htmlspecialchars($item['imagePath']) ?>"
                                                    class="w-full h-full object-cover rounded">
                                            </div>
                                            <div>
                                                <h2 class="font-semibold text-sm sm:text-base lg:text-lg xl:text-xl">
                                                    <?= htmlspecialchars($item['ProductName']) ?>
                                                </h2>
                                                <button type="button"
                                                    class="remove-item-btn text-red-400 hover:underline text-xs sm:text-sm lg:text-base xl:text-lg">Remove</button>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-4 text-center align-middle price"><?= number_format($item['price'], 2) ?>
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

                <div class="order-2 lg:order-2 mt-8 sm:mt-0">
                    <h3 class="text-lg sm:text-xl lg:text-2xl xl:text-3xl font-bold mb-4">Order Summary</h3>
                    <div class="p-4 bg-[#FBF9E4] text-[#122C4F] rounded-lg sticky lg:top-4 border-l-4 shadow-lg">
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







    <footer class="bg-[#122C4F] border-t mt-50">
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
                    class="w-full bg-[#FBF9E4] text-[#122C4F] text-sm py-1 roundedhover:bg-[#FBF9E4]/80 text-center hover:shadow-xl hover:translate-y-[-2px] transition">Subscribe</button>
            </div>
        </div>
    </footer>
</body>


</html>