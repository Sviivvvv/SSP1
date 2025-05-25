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
    'luxuryperfumestore/orderHistory' => 'orderHistory.php',


    //Admin only routes

    'luxuryperfumestore/adminDashboard-Products' => 'adminDashboard-Products.php',
    'luxuryperfumestore/adminDashboard-Users' => 'adminDashboard-Users.php',
    'luxuryperfumestore/adminDashboard-Orders' => 'adminDashboard-Orders.php'

];

// === Serve GET routes ===
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($getRoutes[$requestURL])) {

    $adminRoutes = [
        'luxuryperfumestore/adminDashboard-Products',
        'luxuryperfumestore/adminDashboard-Users',
        'luxuryperfumestore/adminDashboard-Orders',
        'luxuryperfumestore/adminDashboard-Orders/clear-filters' => 'adminDashboard-Orders.php'
    ];

    if (in_array($requestURL, $adminRoutes)) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            http_response_code(404);
            echo "<h1>404 - Page Not Found</h1><p>The page you requested was not found.</p>";
            exit;
        }
    }
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
                echo "<script>alert('Login successful! Welcome admin'); window.location.href = '/luxuryperfumestore/adminDashboard-Products';</script>";
            } else {
                echo "<script>alert('Login successful! Welcome!'); window.location.href = '/luxuryperfumestore/index';</script>";
            }
        }
    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "'); window.location.href = '/luxuryperfumestore/login';</script>";
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
if ($requestURL === 'luxuryperfumestore/adminDashboard-Products' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    }

    try {
        // Add Product
        if (isset($_POST['add_product'])) {
            $required = ['productName', 'price', 'category', 'description', 'imagePath'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("All fields are required");
                }
            }

            $productName = $_POST['productName'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            $description = $_POST['description'];
            $imagePath = $_POST['imagePath'];
            $isSubscription = isset($_POST['isSubscription']) ? 1 : 0;

            $success = adminAddProduct($productName, $price, $category, $description, $imagePath, $isSubscription);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Product added successfully' : 'Failed to add product'
            ]);
            exit;
        }

        // Update Product
        if (isset($_POST['update_product'])) {
            if (empty($_POST['productID'])) {
                throw new Exception("Product ID is required");
            }

            $productID = $_POST['productID'];

            // Filter out empty fields except productID
            $data = [];

            // Only add if not empty (empty string or null), except price can be 0 or "0"
            if (isset($_POST['productName']) && trim($_POST['productName']) !== '') {
                $data['productName'] = $_POST['productName'];
            }
            if (isset($_POST['price']) && $_POST['price'] !== '') {
                // Accept 0 and other numbers
                $data['price'] = $_POST['price'];
            }
            if (isset($_POST['category']) && trim($_POST['category']) !== '') {
                $data['category'] = $_POST['category'];
            }
            if (isset($_POST['description']) && trim($_POST['description']) !== '') {
                $data['description'] = $_POST['description'];
            }
            if (isset($_POST['imagePath']) && trim($_POST['imagePath']) !== '') {
                $data['imagePath'] = $_POST['imagePath'];
            }
            // Only set isSubscription if checkbox is checked
            if (isset($_POST['isSubscription'])) {
                $data['isSubscription'] = 1;
            }

            if (empty($data)) {
                throw new Exception("No fields to update");
            }

            $success = adminUpdateProduct($productID, $data);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Product updated successfully' : 'No changes made'
            ]);
            exit;
        }

        // Delete Product
        if (isset($_POST['delete_product'])) {
            if (empty($_POST['productID'])) {
                throw new Exception("Product ID is required");
            }

            $productID = $_POST['productID'];
            $success = adminDeleteProduct($productID);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Product deleted successfully' : 'Failed to delete product'
            ]);
            exit;
        }

        // If none matched
        throw new Exception("Invalid action");

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

if ($requestURL === 'luxuryperfumestore/adminDashboard-Users' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Admin check
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    }

    try {
        // Add User
        if (isset($_POST['add_user'])) {
            $required = ['userName', 'email', 'password'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("All fields are required");
                }
            }

            $username = $_POST['userName'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $success = adminAddUser($username, $email, $password);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'User added successfully' : 'Failed to add user'
            ]);
            exit;
        }

        // Update User
        if (isset($_POST['update_user'])) {
            if (empty($_POST['userID'])) {
                throw new Exception("User ID is required");
            }

            $userID = $_POST['userID'];
            $data = [];

            if (!empty($_POST['userName'])) {
                $data['username'] = $_POST['userName'];
            }
            if (!empty($_POST['email'])) {
                $data['email'] = $_POST['email'];
            }
            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }
            if (!empty($_POST['subscriptionID'])) {
                $data['subscriptionID'] = $_POST['subscriptionID'];
            }

            if (empty($data)) {
                throw new Exception("No fields to update");
            }

            $success = adminUpdateUsers($userID, $data);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'User updated successfully' : 'No changes made'
            ]);
            exit;
        }

        // Delete User
        if (isset($_POST['delete_user'])) {
            if (empty($_POST['userID'])) {
                throw new Exception("User ID is required");
            }

            $userID = $_POST['userID'];
            $success = adminDeleteUser($userID);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'User deleted successfully' : 'Failed to delete user'
            ]);
            exit;
        }

        throw new Exception("Invalid action");

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

if ($requestURL === 'luxuryperfumestore/adminDashboard-Orders/filter' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $userID = !empty($_POST['userID']) ? $_POST['userID'] : null;
        $orderDate = !empty($_POST['orderDate']) ? $_POST['orderDate'] : null;

        // Validate date format if provided
        if ($orderDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $orderDate)) {
            throw new Exception('Invalid date format. Please use YYYY-MM-DD.');
        }

        // Store filters in session
        $_SESSION['orderFilters'] = [
            'userID' => $userID,
            'orderDate' => $orderDate
        ];

        echo json_encode(['success' => true]);
        exit;

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

if ($requestURL === 'luxuryperfumestore/adminDashboard-Orders/clear-filters' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    }

    unset($_SESSION['orderFilters']);
    header('Location: /luxuryperfumestore/adminDashboard-Orders');
    exit;
}
// === 404 Not Found fallback ===
http_response_code(404);
echo "<h1>404 - Page Not Found</h1><p>The page you requested was not found.</p>";
exit;
