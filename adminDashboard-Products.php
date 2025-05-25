<?php
include_once 'functions.php';
$products = adminsGetAllProducts();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>adminProducts</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="./src/js/main.js" defer></script>
    <script src="./src/js/adminProducts.js" defer></script>
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
    <header>
        <div class="w-full px-8 py-4 relative">
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
        </div>
    </header>


    <main>
        <!--product list table-->
        <section class="flex justify-center mt-30">
            <div class="p-4 rounded-2xl shadow-xl bg-[#FBF9E4] w-full max-w-4xl">
                <h2 class="text-xl font-extrabold text-[#122C4F] mb-3 text-center">Product List</h2>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full text-xs md:text-sm border border-gray-300 rounded-xl">
                        <thead class="bg-[#122C4F] text-[#FBF9E4] uppercase">
                            <tr>
                                <th class="px-2 py-2 text-left">ID</th>
                                <th class="px-2 py-2 text-left">Name</th>
                                <th class="px-2 py-2 text-left">Price</th>
                                <th class="px-2 py-2 text-left">Category</th>
                                <th class="px-2 py-2 text-left">Description</th>
                                <th class="px-10 py-2 text-left">Image</th>
                                <th class="px-2 py-2 text-left">Sub</th>
                                <th class="px-2 py-2 text-left">Deleted</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-[#122C4F]">
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td class="px-2 py-1"><?= $product['productID'] ?></td>
                                    <td class="px-2 py-1 font-semibold"><?= htmlspecialchars($product['ProductName']) ?>
                                    </td>
                                    <td class="px-2 py-1"><?= number_format($product['price'], 2) ?> LKR</td>
                                    <td class="px-2 py-1 capitalize"><?= htmlspecialchars($product['category']) ?></td>
                                    <td class="px-2 py-1 capitalize"><?= htmlspecialchars($product['description']) ?></td>
                                    <td class="px-2 py-2 min-w-[120px]"> <!-- Added min-width to cell -->
                                        <?php if (!$product['isSubscription']): ?>
                                            <img src="<?= htmlspecialchars($product['imagePath']) ?>" alt="Product Image"
                                                class="w-16 h-16 object-cover rounded border mx-auto">
                                        <?php else: ?>
                                            <img src="<?= htmlspecialchars($product['imagePath']) ?>" alt="Product Image"
                                                class="min-w-[120px] w-full h-16 object-cover rounded border">
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-2 py-1">
                                        <?= $product['isSubscription']
                                            ? '<span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-semibold">Yes</span>'
                                            : '<span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-[10px] font-semibold">No</span>' ?>
                                    </td>
                                    <td class="px-2 py-1">
                                        <?= $product['isDeleted']
                                            ? '<span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-[10px] font-semibold">Deleted</span>'
                                            : '<span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-semibold">Active</span>' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>



        <!-- admin manage produt -->
        <div class="container mx-auto px-4 py-9">

            <section class="flex flex-col md:flex-row md:space-x-6 space-y-6 md:space-y-0 mb-6">
                <!-- Add Product -->
                <div class="flex-1 p-6 rounded-lg shadow-lg mt-1">
                    <h2 class="text-xl font-bold mb-4">Add Product</h2>
                    <form id="addForm" class="space-y-4">
                        <input type="hidden" name="add_product" value="1">
                        <input type="text" name="productName" placeholder="Product Name" required
                            class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                        <input type="number" step="0.01" name="price" placeholder="Price (LKR)" required
                            class="w-full md:w-[50%] bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                        <select name="category" required
                            class="w-full md:w-[49%] bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                            <option value="" disabled selected>Select Category</option>
                            <option value="men">men</option>
                            <option value="women">women</option>
                            <option value="limited">limited</option>
                        </select>
                        <textarea name="description" placeholder="Description" required rows="3"
                            class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold"></textarea>
                        <input type="text" name="imagePath" placeholder="Image Path" required
                            class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" name="isSubscription"
                                class="rounded bg-yellow-100 text-[#122C4F]  w-5 h-5">
                            <span class="text-[#FBF9E4] font-bold">Is Subscription</span>
                        </label>
                        <button type="submit" name="add_product"
                            class="bg-[#FBF9E4] text-[#122C4F] px-4 py-2 rounded hover:opacity-90 w-full font-bold hover:bg-[#FBF9E4] hover:shadow-xl hover:translate-y-[2px] transition">Add
                            Product</button>
                    </form>
                </div>

                <!-- Update Product -->
                <div class="flex-1 p-6 rounded-lg shadow-xl ">
                    <h2 class="text-xl font-bold mb-4">Update Product</h2>
                    <form id="updateForm" class="space-y-4 ">
                        <input type="hidden" name="update_product" value="1">
                        <input type="number" name="productID" placeholder="ID" required
                            class="w-50 bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                        <input type="text" name="productName" placeholder="Product Name"
                            class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                        <input type="number" step="0.01" name="price" placeholder="Price (LKR)"
                            class="w-full md:w-[50%] bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                        <select name="category"
                            class="w-full md:w-[49%] bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                            <option value="" disabled selected>Select Category</option>
                            <option value="men">men</option>
                            <option value="women">women</option>
                            <option value="limited">limited</option>
                        </select>
                        <textarea name="description" placeholder="Description" rows="3"
                            class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold"></textarea>
                        <input type="text" name="imagePath" placeholder="Image Path"
                            class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" name="isSubscription"
                                class="rounded bg-yellow-100 text-[#122C4F] w-5 h-5">
                            <span class="text-white font-bold">Is Subscription</span>
                        </label>
                        <button type="submit" name="update_product"
                            class="bg-[#FBF9E4] text-[#122C4F] px-4 py-2 rounded hover:opacity-90 w-full font-bold  hover:bg-[#FBF9E4] hover:shadow-xl hover:translate-y-[2px] transition">Update
                            Product</button>
                    </form>
                </div>
            </section>

            <!-- Delete Product -->
            <section class="grid grid-cols-1">
                <div class="p-6 rounded-lg shadow-lg md:w-1/2 ">
                    <h2 class="text-xl font-bold mb-4">Delete Product</h2>
                    <form id="deleteForm" class="space-y-4">
                        <input type="hidden" name="delete_product" value="1">
                        <input type="number" name="productID" placeholder="ID" required
                            class="w-50 bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                        <button type="submit" name="delete_product"
                            class="bg-[#FBF9E4] text-[#122C4F] px-4 py-2 rounded hover:opacity-90 w-full font-bold  hover:bg-[#FBF9E4] hover:shadow-xl hover:translate-y-[2px] transition">Delete
                            Product</button>
                    </form>
                </div>
            </section>
        </div>
    </main>
</body>



</html>