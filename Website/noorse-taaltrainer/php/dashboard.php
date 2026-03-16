<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}
require_once __DIR__ . '/../php/db.php';

$user_id = (int)$_SESSION['user_id'];
$pdo     = get_db();


$stmt = $pdo->prepare('SELECT username, xp, level, total_score FROM gebruikers WHERE user_id = ? LIMIT 1');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user) { session_destroy(); header('Location: login.html'); exit(); }

$xp       = (int)$user['xp'];
$naam     = htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8');
$level    = (int)$user['level'];
$stars    = min(5, $level - 1);
$progress = ($xp % 50) * 2;


$r_stmt = $pdo->prepare('SELECT COUNT(*) FROM scores WHERE user_id = ?');
$r_stmt->execute([$user_id]);
$rondes = (int)$r_stmt->fetchColumn();


$w_stmt = $pdo->prepare('SELECT COALESCE(SUM(score), 0) FROM scores WHERE user_id = ?');
$w_stmt->execute([$user_id]);
$woorden = (int)$w_stmt->fetchColumn();


$ster_html = str_repeat('⭐', $stars) . str_repeat('☆', 5 - $stars);
$level_label = ($stars >= 5) ? 'Koning 👑' : 'Level ' . $level;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Noorse Taaltrainer</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="topbar">
        <a href="index.html" class="topbar-logo">🇳🇴 Taaltrainer</a>
        <a href="../php/logout.php" class="topbar-btn">🚪 Uitloggen</a>
    </nav>
    <div class="page">
        <div class="card">
            <span class="emoji-big">👋</span>
            <h2 class="text-center">Welkom, <?= $naam ?>!</h2>

            <p class="text-center" style="color:#b19cd9; font-weight:bold;">Jouw voortgang</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width:<?= 100 - $progress ?>%"></div>
            </div>
            <p class="progress-label"><?= $level_label ?> — <?= $progress ?>% compleet</p>

            <div class="stars"><?= $ster_html ?></div>

            <div class="stat-row">
                <div class="stat-box">
                    <div class="stat-value"><?= $level ?></div>
                    <div class="stat-label">Level</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value"><?= $xp ?></div>
                    <div class="stat-label">⚡ XP</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value"><?= $rondes ?></div>
                    <div class="stat-label">🎮 Rondes</div>
                </div>
            </div>

            <hr class="divider">
            <a href="../php/exercise.php" class="btn btn-yellow">🎮 Oefening starten!</a>
            <a href="profile.php" class="btn btn-blue mt">👤 Mijn profiel</a>
        </div>
    </div>
</body>
</html>
