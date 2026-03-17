﻿<?php
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

$ster_html  = str_repeat('⭐', $stars) . str_repeat('☆', 5 - $stars);
$level_label = ($stars >= 5) ? 'Koning 👑' : 'Level ' . $level;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel - Noorse Taaltrainer</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="topbar">
        <a class="topbar-logo">Taaltrainer</a>
        <a href="dashboard.php" class="topbar-btn">🏠 Home</a>
    </nav>
    <div class="page">
        <div class="card">
            <span class="emoji-big">🦊</span>
            <h2 class="text-center">Mijn profiel</h2>
            <p class="text-center" style="font-size:1.2em; font-weight:bold; color:#6d28d9;"><?= $naam ?></p>

            <div class="stars"><?= $ster_html ?></div>
            <p class="text-center" style="color:#b19cd9;"><?= $level_label ?></p>

            <div class="progress-bar" style="margin-top:10px;">
                <div class="progress-fill" style="width:<?= 100 - $progress ?>%"></div>
            </div>
            <p class="progress-label"><?= $progress ?>% naar Level <?= $level + 1 ?></p>

            <div class="stat-row">
                <div class="stat-box">
                    <div class="stat-value"><?= $xp ?></div>
                    <div class="stat-label">⚡ XP</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value"><?= $woorden ?></div>
                    <div class="stat-label">📚 Goed</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value"><?= $rondes ?></div>
                    <div class="stat-label">🎮 Rondes</div>
                </div>
            </div>

            <hr class="divider">
            <a href="../php/exercise.php" class="btn btn-yellow">🎮 Oefenen!</a>
            <a href="../php/logout.php" class="btn btn-pink mt">🚪 Uitloggen</a>

            <?php if (isset($_GET['fout'])): ?>
                <div class="feedback wrong" style="font-size:1em; width:100%; margin-top:14px;">
                    ⚠️ <?= htmlspecialchars($_GET['fout'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <button onclick="document.getElementById('verwijder-modal').style.display='flex'"
                    class="btn-back" style="color:#dc2626; margin-top:24px; border:2px solid #fca5a5; background:#fff1f2;">
                🗑️ Account verwijderen
            </button>

            <a href="dashboard.php" class="btn-back">⬅ Terug</a>
        </div>
    </div>

    <!-- Modal: wachtwoord bevestigen -->
    <div id="verwijder-modal" style="display:none; position:fixed; inset:0; z-index:999;
         background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:16px;">
        <div style="background:white; border-radius:24px; padding:32px; max-width:400px; width:100%;
                    border:3px solid #f87171; box-shadow:0 8px 40px rgba(0,0,0,0.2);">
            <span style="font-size:2.5em; display:block; text-align:center; margin-bottom:8px;">⚠️</span>
            <h2 style="text-align:center; color:#dc2626; margin-bottom:8px;">Account verwijderen</h2>
            <p style="text-align:center; color:#555; margin-bottom:20px;">
                Dit kan <strong>niet</strong> ongedaan worden gemaakt.<br>
                Voer je wachtwoord in om te bevestigen.
            </p>
            <form action="../php/verwijder_account.php" method="post">
                <label for="wachtwoord-bevestig" style="color:#dc2626;">🔒 Wachtwoord</label>
                <input type="password" id="wachtwoord-bevestig" name="wachtwoord"
                       placeholder="Jouw wachtwoord..." required
                       style="border-color:#f87171;">
                <button type="submit" class="btn btn-pink" style="margin-top:16px; background:#dc2626; box-shadow:0 5px 0 #991b1b;">
                    🗑️ Ja, verwijder mijn account
                </button>
            </form>
            <button onclick="document.getElementById('verwijder-modal').style.display='none'"
                    class="btn-back" style="margin-top:12px;">
                ✖ Annuleren
            </button>
        </div>
    </div>

</body>
</html>