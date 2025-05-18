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
    </style>
</head>

<body class="bg-[#122C4F] text-[#FBF9E4] min-h-screen flex flex-col">
    <div class="absolute top-4 left-4">
        <p class="text-xl sm:text-3xl md:text-4xl font-bold">Scent Memoir</p>
    </div>

    <div class="flex flex-1 items-center justify-center">
        <div class="bg-[#FBF9E4] backdrop-blur-lg border border-transparent p-10 rounded-2xl w-full max-w-sm shadow-xl">
            <h2 class="text-center text-3xl font-semibold mb-6 text-[#122C4F]">Sign-Up</h2>
            <form action=" /luxuryperfumestore/signUp" method="POST" class="space-y-4">
                <input type="text" placeholder="Username" name="username" required
                    class="w-full bg-[#EBEBE0]   text-[#122C4F] placeholder-[#122C4F] p-2 rounded focus:outline-none">
                <input type="email" placeholder="Email" name="email" required
                    class="w-full bg-[#EBEBE0] text-[#122C4F] placeholder-[#122C4F] p-2 rounded focus:outline-none">
                <input type="password" placeholder="Password" name="password" required
                    class="w-full bg-[#EBEBE0] text-[#122C4F] placeholder-[#122C4F] p-2 rounded focus:outline-none">
                <div class="flex justify-center">
                    <button type="submit" name="signUp"
                        class=" bg-[#122C4F] text-[#FBF9E4] px-10 p-2 rounded hover:bg-[#122C4F] hover:shadow-xl hover:translate-y-[-2px] transition">Sign
                        Up</button>
                </div>
                <div class="flex justify-center">
                    <a href="/luxuryperfumestore/login"
                        class="text-center text-[#122C4F] underline hover:text-[#000060] hover:underline-offset-2 transition">Login</a>
                </div>
            </form>
        </div>

    </div>

</body>

</html>