<?php
include_once 'functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            font-family: 'Garamond', srif;
        }
    </style>
</head>

<body class="bg-[#122C4F] text-[#FBF9E4] min-h-screen flex flex-col">
    <div class="absolute top-4 left-4">
        <p class="text-xl sm:text-3xl md:text-4xl font-bold">Scent Memoir</p>
    </div>


    <div class="flex flex-1 items-center justify-center">
        <div class="bg-[#FBF9E4] backdrop-blur-lg border border-transparent p-10 rounded-2xl w-full max-w-sm shadow-xl">

            <h2 class="text-center text-3xl font-semibold mb-6 text-[#122C4F]">Login</h2>
            <form action="/luxuryperfumestore/login" method="POST" class="space-y-4">
                <input type="text" placeholder="Username" name="username" required
                    class="w-full bg-[#EBEBE0]  text-[#122C4F] placeholder-[#122C4F] p-2 rounded focus:outline-none">
                <input type="email" placeholder="Email" name="email" required
                    class="w-full bg-[#EBEBE0]   text-[#122C4F] placeholder-[#122C4F] p-2 rounded focus:outline-none">
                <input type="password" placeholder="Password" name="password" required
                    class="w-full bg-[#EBEBE0] text-[#122C4F] placeholder-[#122C4F] p-2 rounded focus:outline-none">
                <div class="flex justify-center">
                    <button type="submit" name="login"
                        class=" bg-[#122C4F] text-[#FBF9E4] px-10 p-2 rounded hover:bg-[#122C4F] hover:shadow-xl hover:translate-y-[-2px] transition">Login</button>
                </div>
                <p class="text-[#122C4F] text-center mt-4">Don't Have An Account?</p>
                <div class="flex justify-center">
                    <a href="/luxuryperfumestore/signUp"
                        class=" text-[#122C4F] text-center underline hover:text-[#122C4F] hover:underline-offset-2 transition">Sign-up</a>
                </div>
            </form>

        </div>
    </div>
</body>

</html>