<?php

include 'functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$requestURL = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// === Supported GET routes ===
$getRoutes = [
    'luxuryperfumestore/login' => 'login.php',
    'luxuryperfumestore/signUp' => 'signUp.php',
    'luxuryperfumestore/index' => 'index.php',
    'luxuryperfumestore/productPage' => 'productPage.php',
    'luxuryperfumestore/cartPage' => 'cartPage.php',
    'luxuryperfumestore/subscriptionPage' => 'subscriptionPage.php',
    'luxuryperfumestore/checkout' => 'checkout.php',
    'luxuryperfumestore/orderHistory' => 'orderHistory.php'
];

// === Serve GET routes ===
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($getRoutes[$requestURL])) {
    include $getRoutes[$requestURL];
    exit;
}

// === POST: Login ===
if ($requestURL === 'luxuryperfumestore/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['username'], $_POST['email'], $_POST['password'])) {
            throw new Exception('All fields are required');
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = loginUser($username, $email, $password);

        if ($user) {
            $_SESSION['userID'] = $user['userID'];
            $role = $user['role'];

            if ($role === 'admin') {
                echo "<script>alert('Login successful! Welcome admin'); window.location.href = 'adminDashboard-AddProducts.php';</script>";
            } else {
                echo "<script>alert('Login successful! Welcome!'); window.location.href = 'index.php';</script>";
            }
        }
    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "'); window.location.href = 'login.php';</script>";
    }
    exit;
}

// === POST: Signup ===
if ($requestURL === 'luxuryperfumestore/signUp' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
            throw new Exception('All fields are required');
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $result = signUpUser($username, $email, $password);

        if ($result === true) {
            echo "<script>alert('Account created successfully!'); window.location.href = 'login.php';</script>";
        } else {
            throw new Exception($result);
        }
    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "'); window.location.href = 'signUp.php';</script>";
    }
    exit;
}

// === POST: Checkout ===
if ($requestURL === 'luxuryperfumestore/checkout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['userID'])) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to checkout.']);
        exit;
    }

    try {
        $required = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'zip', 'payment_method'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields.");
            }
        }

        if ($_POST['payment_method'] === 'card') {
            $cardFields = ['card_name', 'card_number', 'exp_month', 'exp_year', 'cvv'];
            foreach ($cardFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Please fill in all card details.");
                }
            }
        }

        $userID = $_SESSION['userID'];
        $orderID = placeOrder($userID);

        if ($orderID) {
            $_SESSION['last_order_info'] = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'zip' => $_POST['zip'],
                'payment_method' => $_POST['payment_method']
            ];

            echo json_encode([
                'success' => true,
                'message' => 'Order placed successfully!',
                'orderID' => $orderID,
                'redirect' => '/luxuryperfumestore/cartPage'
            ]);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// === POST: Cart Actions ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if (!isset($_SESSION['userID'])) {
        echo json_encode(['success' => false, 'message' => 'Please log in first.']);

        exit;
    }

    $userID = $_SESSION['userID'];

    try {
        if ($action === 'addToCart' && isset($_POST['productID'])) {
            $productID = $_POST['productID'];
            addToCart($userID, $productID);
            echo json_encode(['success' => true, 'message' => 'Product added to cart!']);
            exit;
        }

        if ($action === 'removeCart' && isset($_POST['productID'])) {
            $productID = $_POST['productID'];
            removeFromCart($userID, $productID);
            echo json_encode(['success' => true, 'message' => 'Product removed from cart!']);
            exit;
        }

        if ($action === 'updateCart' && isset($_POST['productID'], $_POST['quantity'])) {
            $productID = $_POST['productID'];
            $quantity = $_POST['quantity'];
            $success = updateCartQuantity($userID, $productID, $quantity);

            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Cart quantity updated!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update quantity.']);
            }
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        exit;

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// === 404 Not Found fallback ===
http_response_code(404);
echo "<h1>404 - Page Not Found</h1><p>The page you requested was not found.</p>";
exit;
