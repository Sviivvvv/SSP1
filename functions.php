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
    $pdo = getDBConnection();

    if ($pdo) {
        $sql = "SELECT * FROM users WHERE username = :username AND email = :email AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "Login Successful!";

        } else {
            echo "Login Failed. Incorrect username, email, or password.";
        }
    } else {
        echo "Database connection failed.";
    }
}