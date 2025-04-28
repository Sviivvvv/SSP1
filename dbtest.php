<?php
include "functions.php";

$users = GetUsers();
echo json_encode(["users"=>$users])
?>