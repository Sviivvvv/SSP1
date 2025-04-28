<?php
session_start();
require_once 'functions.php';

$requestURL = trim($_SERVER['REQUEST_URI'], '/');

if ($requestURL === 'luxuryperfumestore/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $error = loginUser($username, $email, $password);

    if ($error) {
        header("Location: /?error=$error"); // no urlencode
    } else {
        $redirect = '/luxuryperfumestore/index.php'; // default after login

        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $redirect = '';
        }

        header("Location: $redirect");
    }
    exit();
}
http_response_code(404);
echo "404 Not Found";
?>