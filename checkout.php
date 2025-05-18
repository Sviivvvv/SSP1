<?php
require_once 'functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['userID'])) {
    echo "<script>
    alert('You must be logged in to checkout.');
    window.location.href = '/luxuryperfumestore/login';
    </script>";
    exit;
}


$userID = $_SESSION['userID'];


// Get the user's cart items and subtotal
$cartItems = getCartItems($userID);
$subtotal = getCartSubtotal($userID);



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="output.css" rel="stylesheet">
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
    <section class="p-4 sm:p-6 mt-20 w-full max-w-screen-xl mx-auto text-[#FBF9E4]">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Order Summary -->
            <div class="lg:w-1/3 mt-3 font-semibold">
                <h3 class="text-2xl sm:text-xl xl:text-3xl font-bold mb-4">
                    Order Summary
                </h3>
                <div class="p-4 bg-[#FBF9E4] text-[#122C4F] rounded-lg border-l-4 shadow-lg w-full 
                lg:sticky lg:top-4">
                    <!-- Product List -->
                    <div class="space-y-3 mb-4">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="flex justify-between text-sm sm:text-base lg:text-lg xl:text-xl">
                                <span class="block w-2/3 break-words"><?= htmlspecialchars($item['ProductName']) ?></span>
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


    <script>
        // Toggle card fields based on payment method
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        const cardFields = document.getElementById('cardFields');

        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'cod') {
                    cardFields.style.display = 'none';
                    // Make card fields not required when COD is selected
                    cardFields.querySelectorAll('[required]').forEach(field => {
                        field.required = false;
                    });
                } else {
                    cardFields.style.display = 'block';
                    // Make card fields required when card is selected
                    cardFields.querySelectorAll('[required]').forEach(field => {
                        field.required = true;
                    });
                }
            });
        });

        // Basic form validation before submission
        const form = document.getElementById('checkoutForm');
        form.addEventListener('submit', function (e) {
            // Validate card details if card payment is selected
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

            if (paymentMethod === 'card') {
                const cardNumber = form.card_number.value.trim();
                const cvv = form.cvv.value.trim();

                // Simple card number validation (16 digits)
                if (!/^\d{16}$/.test(cardNumber)) {
                    alert('Please enter a valid 16-digit card number');
                    e.preventDefault();
                    return;
                }

                // Simple CVV validation (3-4 digits)
                if (!/^\d{3,4}$/.test(cvv)) {
                    alert('Please enter a valid CVV (3-4 digits)');
                    e.preventDefault();
                    return;
                }
            }

            // You could add more validation here if needed
            // For example:
            // - Email format validation
            // - Phone number validation
            // - Zip code validation

            // If everything is valid, the form will submit normally
        });
        document.getElementById('checkoutForm').addEventListener('submit', async function (e) {
            e.preventDefault(); // Prevent default form submission

            // Validate form first (reuse your existing validation logic)
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

            if (paymentMethod === 'card') {
                const cardNumber = this.card_number.value.trim();
                const cvv = this.cvv.value.trim();

                if (!/^\d{16}$/.test(cardNumber)) {
                    alert('Please enter a valid 16-digit card number');
                    return;
                }

                if (!/^\d{3,4}$/.test(cvv)) {
                    alert('Please enter a valid CVV (3-4 digits)');
                    return;
                }
            }

            // Submit via Fetch API
            try {
                const formData = new FormData(this);
                const response = await fetch('/luxuryperfumestore/checkout', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert(`Order #${result.orderID} placed successfully!`);
                    window.location.href = result.redirect; // Redirect to home
                } else {
                    alert(`Error: ${result.message}`);
                }
            } catch (error) {
                alert("Checkout failed. Please try again.");
                console.error(error);
            }
        });
    </script>


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