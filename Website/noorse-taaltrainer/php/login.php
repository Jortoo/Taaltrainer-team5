<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/login.html');
    exit();
}

$gebruikersnaam = trim($_POST['username'] ?? '');
$wachtwoord     = $_POST['password'] ?? '';

if ($gebruikersnaam === '' || $wachtwoord === '') {
    header('Location: ../pages/login.html?fout=' . urlencode('Vul alle velden in.'));
    exit();
}

try {
    $pdo  = get_db();
    $stmt = $pdo->prepare(
        'SELECT user_id, username, wachtwoord, level FROM gebruikers WHERE username = ? LIMIT 1'
    );
    $stmt->execute([$gebruikersnaam]);
    $user = $stmt->fetch();

    if ($user && password_verify($wachtwoord, $user['wachtwoord'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']  = (int)$user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['level']    = (int)$user['level'];
        header('Location: ../pages/dashboard.php');
        exit();
    }

    header('Location: ../pages/login.html?fout=' . urlencode('Gebruikersnaam of wachtwoord klopt niet.'));
    exit();

} catch (PDOException $e) {
    header('Location: ../pages/login.html?fout=' . urlencode('Databasefout — probeer het later opnieuw.'));
    exit();
}
