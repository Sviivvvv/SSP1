<?php
include_once 'functions.php';
$users = adminGetAllUsers();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>adminUsers</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="./src/js/main.js" defer></script>
    <script src="./src/js/adminUsers.js" defer></script>
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

    <!-- available customer table -->
    <section class="flex justify-center mt-30">
        <div class="p-4 rounded-2xl shadow-xl bg-[#FBF9E4] w-full max-w-4xl">
            <h2 class="text-xl font-extrabold text-[#122C4F] mb-3 text-center">Customer List</h2>
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-xs md:text-sm border border-gray-300 rounded-xl">
                    <thead class="bg-[#122C4F] text-[#FBF9E4] uppercase">
                        <tr>
                            <th class="px-2 py-2 text-left">ID</th>
                            <th class="px-2 py-2 text-left">Username</th>
                            <th class="px-2 py-2 text-left">Email</th>
                            <th class="px-2 py-2 text-left">Password</th>
                            <th class="px-2 py-2 text-left">Subscription</th>
                            <th class="px-2 py-2 text-left">Role</th>
                            <th class="px-2 py-2 text-left">Deleted</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-[#122C4F]">
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="px-2 py-1"><?= $user['userID'] ?></td>
                                <td class="px-2 py-1 font-semibold"><?= htmlspecialchars($user['username']) ?></td>
                                <td class="px-2 py-1"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="px-2 py-1 break-all"><?= htmlspecialchars($user['password']) ?></td>
                                <td class="px-2 py-1">
                                    <?= $user['subscriptionID']
                                        ? '<span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-semibold">' . $user['subscriptionID'] . '</span>'
                                        : '<span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-[10px] font-semibold">None</span>' ?>
                                </td>
                                <td class="px-2 py-1 capitalize"><?= htmlspecialchars($user['role']) ?></td>
                                <td class="px-2 py-1">
                                    <?= $user['isDeleted']
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


    <div class="container mx-auto px-4 py-9">
        <!--admin manage users section -->
        <section class="flex flex-col md:flex-row md:space-x-6 space-y-6 md:space-y-0 mb-6">
            <!-- Add users -->
            <div class="flex-1 p-6 rounded-lg shadow-lg mt-1">
                <h2 class="text-xl font-bold mb-4">Add User</h2>
                <form id="addForm" class="space-y-4">
                    <input type="hidden" name="add_user" value="1">
                    <input type="text" name="userName" placeholder="UserName" required
                        class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                    <input type="email" step="0.01" name="email" placeholder="Email" required
                        class="w-full  bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                    <input type="text" name="password" placeholder="Password" required
                        class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">

                    <button type="submit" name="add_user"
                        class="bg-[#FBF9E4] text-[#122C4F] px-4 py-2 rounded hover:opacity-90 w-full font-bold hover:bg-[#FBF9E4] hover:shadow-xl hover:translate-y-[2px] transition">Add
                        User</button>
                </form>
            </div>

            <!-- Update users -->
            <div class="flex-1 p-6 rounded-lg shadow-xl ">
                <h2 class="text-xl font-bold mb-4">Update User</h2>
                <form id="updateForm" class="space-y-4 ">
                    <input type="hidden" name="update_user" value="1">
                    <input type="number" name="userID" placeholder="ID" required
                        class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                    <input type="text" name="userName" placeholder="UserName"
                        class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                    <input type="email" name="email" placeholder="Email"
                        class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                    <input type="text" name="password" placeholder="Password"
                        class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                    <input type="number" name="subscriptionID" placeholder="Subscription ID"
                        class="w-full bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                    <button type="submit" name="update_user"
                        class="bg-[#FBF9E4] text-[#122C4F] px-4 py-2 rounded hover:opacity-90 w-full font-bold  hover:bg-[#FBF9E4] hover:shadow-xl hover:translate-y-[2px] transition">Update
                        User</button>
                </form>
            </div>
        </section>

        <!-- Delete users -->
        <section class="grid grid-cols-1">
            <div class="p-6 rounded-lg shadow-lg md:w-1/2 ">
                <h2 class="text-xl font-bold mb-4">Delete User</h2>
                <form id="deleteForm" class="space-y-4">
                    <input type="hidden" name="delete_user" value="1">
                    <input type="number" name="userID" placeholder="ID" required
                        class="w-50 bg-[#FBF9E4] text-[#122C4F] p-2 rounded font-bold">
                    <button type="submit" name="delete_user"
                        class="bg-[#FBF9E4] text-[#122C4F] px-4 py-2 rounded hover:opacity-90 w-full font-bold  hover:bg-[#FBF9E4] hover:shadow-xl hover:translate-y-[2px] transition">Delete
                        User</button>
                </form>
            </div>
        </section>
    </div>
</body>


</html>