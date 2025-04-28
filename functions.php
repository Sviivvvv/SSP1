<?php

//Database connection
define('DB_HOST', 'localhost');
define('DB_NAME', 'luxury_perfume_store');
define('DB_USER', 'root');
define('DB_PASS', '');

//Function to connect to DB
function getDBConnection()
{
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection Failed: " . $e->getMessage());
    }
}

//login function

function loginUser($username, $email, $password)
{
    $pdo = getDBConnection();

    if ($pdo) {

        $query = "SELECT * FROM users WHERE username = ? AND email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username, $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['password']) {
                return $user;
            } else {
                return "invalid password";

            }

        } else {
            return "login Failed. Incorrect username or email";
        }
    } else {
        return "Database connection failed";
    }
}