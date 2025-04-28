<?php
include 'functions.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    loginUser($username, $email, $password);
}
?>