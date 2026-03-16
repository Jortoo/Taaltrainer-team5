<?php
// register.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Demo: registratie slaat niets op
    header('Location: ../pages/login.html');
    exit();
}
?>