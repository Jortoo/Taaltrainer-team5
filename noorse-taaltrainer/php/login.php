<?php
// login.php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Demo: accepteer elke gebruiker
    $_SESSION['username'] = $username;
    header('Location: ../pages/dashboard.html');
    exit();
}
?>