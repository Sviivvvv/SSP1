<?php

//Database connection
define('DB_HOST', 'localhost');
define('DB_NAME', 'luxury_perfume_store');
define('DB_USER', 'root');
define('DB_PASS', '');



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
function isLoggedIn()
{
    return isset($_SESSION['userID']);
}
//login function

function loginUser($username, $email, $password)
{
    $pdo = getDBConnection();


    $query = "SELECT * FROM users WHERE username = ? AND email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username, $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (!empty($user['isDeleted']) && $user['isDeleted'] == 1) {
            throw new Exception('Account unavailable');
        }
        if ($password === $user['password']) {
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return $user;
        } else {
            throw new Exception('Invalid password.');
        }
    } else {
        throw new Exception('login failed. Incorrect username or email.');
    }
}

function signUpUser($username, $email, $password)
{
    $pdo = getDBConnection();

    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username, $email]);

    if ($stmt->fetch()) {
        return "Username or email already exists.";
    }

    $role = 'customer';

    $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";

    $stmt = $pdo->prepare($query);
    $sucess = $stmt->execute([$username, $email, $password, $role]);

    return $sucess ? true : "Registration failed. Please try again";

    //rember to change to if else
}

function getLimitedTimeProducts()
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM product WHERE category = 'limited'AND isDeleted = 0");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMensProducts()
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM product WHERE category = 'men'AND isDeleted = 0");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getWomensProducts()
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM product WHERE category = 'women'AND isDeleted = 0");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// NEW FUNCTIONS


// Add to cart function
function addToCart($userID, $productID, $quantity = 1)
{
    $pdo = getDBConnection();

    // Get or create cart
    $stmt = $pdo->prepare("SELECT cartID FROM cart WHERE userID = ?");
    $stmt->execute([$userID]);
    $cart = $stmt->fetch();

    if (!$cart) {
        $stmt = $pdo->prepare("INSERT INTO cart (userID) VALUES (?)");
        $stmt->execute([$userID]);
        $cartID = $pdo->lastInsertId();
    } else {
        $cartID = $cart['cartID'];
    }

    //Check is product is a subscription

    $stmt = $pdo->prepare("SELECT isSubscription FROM product WHERE productID =?");
    $stmt->execute([$productID]);
    $product = $stmt->fetch();


    if ($product && $product['isSubscription'] == 1) {
        $stmt = $pdo->prepare("SELECT * FROM cartProduct cp JOIN product p ON cp.productID = p.productID WHERE cp.cartID= ? AND p.isSubscription = 1");

        $stmt->execute([$cartID]);
        $existingSubscription = $stmt->fetch();

        if ($existingSubscription) {
            throw new Exception("You can only have one subscription at a time.");
        }
    }

    // Check if product exists in cart
    $stmt = $pdo->prepare("SELECT * FROM cartProduct WHERE cartID = ? AND productID = ?");
    $stmt->execute([$cartID, $productID]);
    $existingItem = $stmt->fetch();

    if ($existingItem) {
        $newQuantity = $existingItem['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cartProduct SET quantity = ? WHERE cartID = ? AND productID = ?");
        $result = $stmt->execute([$newQuantity, $cartID, $productID]);
    } else {

        $stmt = $pdo->prepare("INSERT INTO cartProduct (cartID, productID, quantity) VALUES (?, ?, ?)");
        $result = $stmt->execute([$cartID, $productID, $quantity]);
    }

    return true;
}

function userSubscription($userID, $subscriptionID)
{
    $pdo = getDBConnection();

    //making sure only one sub at a time

    $stmt = $pdo->prepare("SELECT subscriptionID FROM users WHERE userID =? AND subscriptionID =?");
    $stmt->execute([$userID, $subscriptionID]);
    $currentSubscription = $stmt->fetch();

    if ($currentSubscription) {
        throw new Exception("You already have this subscription active.");
    }
    $stmt = $pdo->prepare("UPDATE users SET subscriptionID = ? WHERE userID =?");
    $stmt->execute([$subscriptionID, $userID]);

    return true;
}
// Updated removeFromCart function
function removeFromCart($userID, $productID)
{
    $pdo = getDBConnection();

    // Get the user's cartID
    $stmt = $pdo->prepare("SELECT cartID FROM cart WHERE userID = ?");
    $stmt->execute([$userID]);
    $cart = $stmt->fetch();

    if ($cart) {
        $stmt = $pdo->prepare("DELETE FROM cartProduct WHERE cartID = ? AND productID = ?");
        return $stmt->execute([$cart['cartID'], $productID]);
    }
    return false;
}

// Updated updateCartQuantity function
function updateCartQuantity($userID, $productID, $quantity)
{
    $pdo = getDBConnection();

    // Get the user's cartID
    $stmt = $pdo->prepare("SELECT cartID FROM cart WHERE userID = ?");
    $stmt->execute([$userID]);
    $cart = $stmt->fetch();

    if (!$cart)
        return false;



    $stmt = $pdo->prepare("UPDATE cartProduct SET quantity = ? WHERE cartID = ? AND productID = ?");
    return $stmt->execute([$quantity, $cart['cartID'], $productID]);
}

// Updated getCartItems function
function getCartItems($userID)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT p.productID, p.ProductName, p.price, p.imagePath, p.isSubscription, cp.quantity 
        FROM cart c
        JOIN cartProduct cp ON c.cartID = cp.cartID
        JOIN product p ON cp.productID = p.productID
        WHERE c.userID = ?
    ");
    $stmt->execute([$userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// getCartSubtotal remains the same
function getCartSubtotal($userID)
{
    $items = getCartItems($userID);
    $subtotal = 0;

    foreach ($items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    return $subtotal;
}


function addOrderItem($orderID, $productID, $price, $quantity)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO orderItem (orderID, productID, price, quantity) VALUES (?,?,?,?)");
    return $stmt->execute([$orderID, $productID, $price, $quantity]);

}

function clearCart($userID)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT cartID FROM cart WHERE userID = ?");
    $stmt->execute([$userID]);
    $cart = $stmt->fetch();

    if ($cart) {
        $stmt = $pdo->prepare("DELETE FROM cartProduct WHERE cartID =?");
        $stmt->execute([$cart['cartID']]);
    }


}


/*function placeOrder($userID)
{
    $pdo = getDBConnection();
    $cartItems = getCartItems($userID);
    $totalAmount = getCartSubtotal($userID);


    if (empty($cartItems)) {
        return false;
    }

    // Check for multiple subscriptions in cart
    $subscriptionCount = 0;
    $subscriptionID = null;
    foreach ($cartItems as $item) {
        if ($item['isSubscription'] == 1) {
            $subscriptionCount++;
            $subscriptionID = $item['productID'];
        }
    }

    if ($subscriptionCount > 1) {
        throw new Exception("You can only have one subscription at a time.");
    }

    $stmt = $pdo->prepare("INSERT INTO orders (userID, orderDate, totalAmount) VALUES (?, NOW(), ?)");
    $stmt->execute([$userID, $totalAmount]);
    $orderID = $pdo->lastInsertId();

    foreach ($cartItems as $item) {
        addOrderItem($orderID, $item['productID'], $item['price'], $item['quantity']);
    }

    //checking if sub is in cart

    if ($subscriptionID) {
        try {
            userSubscription($userID, $subscriptionID);
        } catch (Exception $e) {
            // If subscription fails, cancel the order
            $pdo->prepare("DELETE FROM orders WHERE orderID = ?")->execute([$orderID]);
            $pdo->prepare("DELETE FROM orderItem WHERE orderID = ?")->execute([$orderID]);
            throw $e; // Re-throw the exception
        }
    }

    clearCart($userID);

    return $orderID;
} */

function placeOrder($userID)
{
    $pdo = getDBConnection();
    $cartItems = getCartItems($userID);
    $totalAmount = getCartSubtotal($userID);

    if (empty($cartItems)) {
        return false;
    }

    // Check for multiple subscriptions in cart
    $subscriptionCount = 0;
    $subscriptionID = null;
    foreach ($cartItems as $item) {
        if ($item['isSubscription'] == 1) {
            $subscriptionCount++;
            $subscriptionID = $item['productID'];
        }
    }

    if ($subscriptionCount > 1) {
        throw new Exception("You can only have one subscription at a time.");
    }

    // Check if user already has the same subscription active
    if ($subscriptionID !== null) {
        $stmt = $pdo->prepare("SELECT subscriptionID FROM users WHERE userID = ?");
        $stmt->execute([$userID]);
        $existingSubscription = $stmt->fetchColumn();

        if ($existingSubscription == $subscriptionID) {
            throw new Exception("You already have this subscription active.");
        }
    }

    // Proceed with order
    $stmt = $pdo->prepare("INSERT INTO orders (userID, orderDate, totalAmount) VALUES (?, NOW(), ?)");
    $stmt->execute([$userID, $totalAmount]);
    $orderID = $pdo->lastInsertId();

    foreach ($cartItems as $item) {
        addOrderItem($orderID, $item['productID'], $item['price'], $item['quantity']);
    }

    if ($subscriptionID) {
        try {
            userSubscription($userID, $subscriptionID);
        } catch (Exception $e) {
            // Cancel order if subscription activation fails
            $pdo->prepare("DELETE FROM orderItem WHERE orderID = ?")->execute([$orderID]);
            $pdo->prepare("DELETE FROM orders WHERE orderID = ?")->execute([$orderID]);
            throw $e;
        }
    }

    clearCart($userID);

    return $orderID;
}


function getSubscriptionProducts()
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM product WHERE isSubscription = 1 AND isDeleted = 0");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderHistory($userID)
{
    $pdo = getDBConnection();
    $sql = "
        SELECT 
            o.orderID,
            o.orderDate,
            oi.productID,
            p.productName,
            oi.quantity,
            oi.price,
            (oi.quantity * oi.price) as totalPrice
        FROM orders o
        JOIN orderItem oi ON o.orderID = oi.orderID
        JOIN product p ON oi.productID = p.productID
        WHERE o.userID = ?
        ORDER BY o.orderDate DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGroupedOrderHistory($userID)
{
    // First get the raw order history
    $orderHistory = getOrderHistory($userID);

    // Group orders by orderID
    $groupedOrders = [];

    foreach ($orderHistory as $order) {
        $orderID = $order['orderID'];

        if (!isset($groupedOrders[$orderID])) {
            $groupedOrders[$orderID] = [
                'orderDate' => $order['orderDate'],
                'items' => [],
                'orderTotal' => 0
            ];
        }

        $groupedOrders[$orderID]['items'][] = $order;
        $groupedOrders[$orderID]['orderTotal'] += $order['totalPrice'];
    }

    return $groupedOrders;
}

function adminAddProduct($productName, $price, $category, $description, $imagePath, $isSubscription)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO product (productName, price, category, description, imagePath, isSubscription) VAlUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$productName, $price, $category, $description, $imagePath, $isSubscription ? 1 : 0]);
}
function adminAddUser($username, $email, $password)
{
    $pdo = getDBConnection();
    $subscriptionID = 0;
    $role = 'customer';
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, subscriptionID, role ) VAlUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$username, $email, $password, $subscriptionID, $role]);
}

function adminUpdateProduct($productID, $data)
{
    $pdo = getDBConnection();
    // Get current product
    $stmt = $pdo->prepare("SELECT * FROM product WHERE productID = ?");
    $stmt->execute([$productID]);
    if (!$stmt->fetch())
        throw new Exception("Product not found");

    // Build update query
    $fields = [];
    $values = [];

    if (isset($data['productName'])) {
        $fields[] = "productName = ?";
        $values[] = $data['productName'];
    }
    if (isset($data['price'])) {
        $fields[] = "price = ?";
        $values[] = $data['price'];
    }
    if (isset($data['category'])) {
        $fields[] = "category = ?";
        $values[] = $data['category'];
    }
    if (isset($data['description'])) {
        $fields[] = "description = ?";
        $values[] = $data['description'];
    }
    if (isset($data['imagePath'])) {
        $fields[] = "imagePath = ?";
        $values[] = $data['imagePath'];
    }
    if (isset($data['isSubscription'])) {
        $fields[] = "isSubscription = ?";
        $values[] = $data['isSubscription'] ? 1 : 0;
    }

    if (empty($fields))
        return false;

    $values[] = $productID;
    $query = "UPDATE product SET " . implode(", ", $fields) . " WHERE productID = ?";
    $stmt = $pdo->prepare($query);
    return $stmt->execute($values);
}

function adminUpdateUsers($userID, $data)
{
    $pdo = getDBConnection();
    // Get current product
    $stmt = $pdo->prepare("SELECT * FROM users WHERE userID = ?");
    $stmt->execute([$userID]);
    if (!$stmt->fetch())
        throw new Exception("user not found");

    // Build update query
    $fields = [];
    $values = [];

    if (isset($data['username'])) {
        $fields[] = "username = ?";
        $values[] = $data['username'];
    }
    if (isset($data['email'])) {
        $fields[] = "email = ?";
        $values[] = $data['email'];
    }
    if (isset($data['password'])) {
        $fields[] = "password = ?";
        $values[] = $data['password'];
    }
    if (isset($data['subscriptionID'])) {
        $fields[] = "subscriptionID = ?";
        $values[] = $data['subscriptionID'];
    }


    if (empty($fields))
        return false;

    $values[] = $userID;
    $query = "UPDATE users SET " . implode(", ", $fields) . " WHERE userID = ?";
    $stmt = $pdo->prepare($query);
    return $stmt->execute($values);
}
function adminDeleteProduct($productID)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE product SET isDeleted = 1 WHERE productID = ?");
    return $stmt->execute([$productID]);
}

function adminDeleteUser($userID)
{

    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT role FROM users WHERE userID =?");
    $stmt->execute([$userID]);
    $user = $stmt->fetch();

    if ($user && $user['role'] === 'customer') {
        $stmt = $pdo->prepare("UPDATE users SET isDeleted = 1 WHERE userID = ?");
        return $stmt->execute([$userID]);
    }

    return false;
}

function adminsGetAllProducts()
{
    $pdo = getDBConnection();
    $sql = "SELECT * FROM product";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function adminGetAllUsers()
{
    $pdo = getDBConnection();
    $sql = "SELECT * FROM users WHERE role ='customer' ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

function adminGetAllOrders()
{
    $pdo = getDBConnection();
    $sql = "
        SELECT o.orderID, o.userID, o.orderDate, o.totalAmount,
               oi.productID, p.productName, oi.price, oi.quantity
        FROM orders o
        JOIN orderItem oi ON o.orderID = oi.orderID
        JOIN product p ON oi.productID = p.productID
        ORDER BY o.orderDate DESC, o.orderID DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function adminFilterOrders($userID = null, $date = null) {
    $pdo = getDBConnection();
    
    $sql = "
        SELECT o.orderID, o.userID, o.orderDate, o.totalAmount,
               oi.productID, p.productName, oi.price, oi.quantity
        FROM orders o
        JOIN orderItem oi ON o.orderID = oi.orderID
        JOIN product p ON oi.productID = p.productID
        WHERE 1=1";
    
    $params = [];
    
    if ($userID) {
        $sql .= " AND o.userID = :userID";
        $params[':userID'] = $userID;
    }
    
    if ($date) {
        $sql .= " AND DATE(o.orderDate) = :orderDate";
        $params[':orderDate'] = $date;
    }
    
    $sql .= " ORDER BY o.orderDate DESC, o.orderID DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
