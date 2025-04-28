<?php
include 'functions.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $message = loginUser($username, $email, $password);

    if (is_array($message)) {
        $role = $message['role'];
        if ($role === 'admin') {
            echo "<script>
            alert('login sucessful !welcome admin');
            window.location.href = 'adminDashboard-AddProducts.php';
            
            </script>";
        } else {
            echo "<script>
                alert('Login Successful!');
                window.location.href = 'index.php';
            </script>";
        }
    } else {
        echo "<script>
        alert('$message');
        window.location.href = 'login.php';
    </script>";
    }
}
?>