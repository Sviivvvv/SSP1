<?php

//Database connection
$host = "localhost";
$dbname = "luxury_perfume_store";
$username = "root";
$password = "";

//Function to connect to DB
function getDBConnection()
{
    global $host, $dbname, $username, $password;
    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname",
            $username,
            $password
        );
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        return $pdo;
    } catch (PDOException $e) {
        echo "Connection Failed: " . $e->getMessage();

    }
}


//login function

function loginUser($username, $email, $password)
{
    $conn = getDBConnection();


    if (!empty($username)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

    } elseif (!empty($email)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
    } else {
        return "Please enter a username or email";
    }
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return "User Not Found";
    }
    if ($password !== $user['password']) {
        return "Incorrect password";

    }
    $_SESSION['userID'] = $user['userID'];
    $_SESSION['userName'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    return null;

}

function GetUsers(){
    $conn = getDBConnection();
    $query = "SELECT * FROM users";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);    
}