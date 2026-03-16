<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.html');
    exit();
}
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/profile.php');
    exit();
}

$user_id    = (int)$_SESSION['user_id'];
$wachtwoord = $_POST['wachtwoord'] ?? '';

if ($wachtwoord === '') {
    header('Location: ../pages/profile.php?fout=' . urlencode('Vul je wachtwoord in.'));
    exit();
}

try {
    $pdo  = get_db();
    $stmt = $pdo->prepare('SELECT wachtwoord FROM gebruikers WHERE user_id = ? LIMIT 1');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($wachtwoord, $user['wachtwoord'])) {
        header('Location: ../pages/profile.php?fout=' . urlencode('Wachtwoord klopt niet. Account is niet verwijderd.'));
        exit();
    }

    // Verwijder het account (scores worden via ON DELETE SET NULL bewaard)
    $pdo->prepare('DELETE FROM gebruikers WHERE user_id = ?')->execute([$user_id]);

    session_unset();
    session_destroy();
    header('Location: ../pages/index.html?bericht=' . urlencode('Je account is verwijderd.'));
    exit();

} catch (PDOException $e) {
    header('Location: ../pages/profile.php?fout=' . urlencode('Er is een fout opgetreden. Probeer het later opnieuw.'));
    exit();
}
