<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles.css" rel="stylesheet">
</head>

<body>

    <form action="/luxuryperfumestore/login" method="POST">
        <input type="text" placeholder="Username (optional)" name="username">
        <input type="email" placeholder="Email (optional)" name="email">
        <input type="password" placeholder="Password" name="password" required>
        <button type="submit">Login</button>
        <p>Don't Have An Account?</p>
        <a href="/signUp.php">Sign-up</a>
    </form>
</body>

</html>