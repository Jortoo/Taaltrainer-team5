<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/register.html');
    exit();
}

$gebruikersnaam = trim($_POST['username'] ?? '');
$email          = trim($_POST['email']    ?? '');
$wachtwoord     = $_POST['password']      ?? '';

if ($gebruikersnaam === '' || $email === '' || $wachtwoord === '') {
    header('Location: ../pages/register.html?fout=' . urlencode('Vul alle velden in.'));
    exit();
}
if (strlen($gebruikersnaam) < 3) {
    header('Location: ../pages/register.html?fout=' . urlencode('Gebruikersnaam moet minstens 3 tekens zijn.'));
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../pages/register.html?fout=' . urlencode('Vul een geldig e-mailadres in.'));
    exit();
}
if (strlen($wachtwoord) < 6) {
    header('Location: ../pages/register.html?fout=' . urlencode('Wachtwoord moet minstens 6 tekens zijn.'));
    exit();
}

try {
    $pdo = get_db();

    $check = $pdo->prepare(
        'SELECT user_id FROM gebruikers WHERE username = ? OR email = ? LIMIT 1'
    );
    $check->execute([$gebruikersnaam, $email]);
    if ($check->fetch()) {
        header('Location: ../pages/register.html?fout=' . urlencode('Gebruikersnaam of e-mail is al in gebruik.'));
        exit();
    }

    $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        'INSERT INTO gebruikers (username, email, wachtwoord) VALUES (?, ?, ?)'
    );
    $stmt->execute([$gebruikersnaam, $email, $hash]);

    session_regenerate_id(true);
    $_SESSION['user_id']  = (int)$pdo->lastInsertId();
    $_SESSION['username'] = $gebruikersnaam;
    header('Location: ../pages/dashboard.php');
    exit();

} catch (PDOException $e) {
    header('Location: ../pages/register.html?fout=' . urlencode('Databasefout — probeer het later opnieuw.'));
    exit();
}
