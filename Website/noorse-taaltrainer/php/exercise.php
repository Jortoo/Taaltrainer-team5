<?php
session_start();
require_once 'functions.php';

$total = geef_totaal_vragen();

if (!isset($_SESSION['q_index'])) $_SESSION['q_index'] = 0;
if (!isset($_SESSION['q_score'])) $_SESSION['q_score'] = 0;

$feedback = '';
$selected = '';
$answered = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['volgende'])) {
        $_SESSION['q_index']++;
        if ((int)$_SESSION['q_index'] >= $total) {
            $score = (int)$_SESSION['q_score'];
            $tot   = $total;
            $_SESSION['q_index'] = 0;
            $_SESSION['q_score'] = 0;
            $uid = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
            sla_score_op($uid, $score, $tot);
            header('Location: ../pages/score.html?score=' . $score . '&total=' . $tot);
            exit();
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['answer'])) {
        $answer   = htmlspecialchars(trim($_POST['answer']), ENT_QUOTES, 'UTF-8');
        $selected = $answer;
        $vd       = haal_vraag_op((int)$_SESSION['q_index']);
        $isGoed   = ($answer === $vd['goed']);
        if ($isGoed) $_SESSION['q_score']++;
        $feedback = toon_feedback($isGoed);
        $answered = true;
    }
}

$index       = (int)$_SESSION['q_index'];
$vraagdata   = haal_vraag_op($index);
$fill_pct    = (int)round((1 - $index / $total) * 100);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oefening – Noorse Taaltrainer</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="topbar">
    <a href="../pages/index.html" class="topbar-logo">🇳🇴 Taaltrainer</a>
    <a href="../pages/dashboard.html" class="topbar-btn">🏠 Dashboard</a>
</nav>

<div class="page">
    <div class="card">

        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
            <span style="font-weight:bold; color:#5c3d99;">⭐ Vraag <?php echo $index + 1; ?> van <?php echo $total; ?></span>
            <span style="color:#888; font-size:0.9em;"><?php echo (int)round(($_SESSION['q_score'] / max($index, 1)) * 100); ?>% goed</span>
        </div>

        <div class="progress-bar">
            <div class="progress-fill" style="width:<?php echo $fill_pct; ?>%"></div>
        </div>

        <div class="question-box">
            <p class="question-text"><?php echo htmlspecialchars($vraagdata['vraag'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <?php if (!$answered): ?>
        <form action="" method="post">
            <?php echo genereer_antwoorden($vraagdata['antwoorden'], $selected); ?>
            <button type="submit" class="btn btn-yellow" style="margin-top:24px;">✅ Controleer antwoord</button>
        </form>
        <?php else: ?>
            <?php echo $feedback; ?>
            <form action="" method="post" style="margin-top:18px;">
                <input type="hidden" name="volgende" value="1">
                <button type="submit" class="btn btn-blue">
                    <?php echo ($index + 1 >= $total) ? '🏆 Bekijk resultaten' : '➡️ Volgende vraag'; ?>
                </button>
            </form>
        <?php endif; ?>

        <a href="../pages/dashboard.php" class="btn-back">⬅ Terug naar dashboard</a>

    </div>
</div>

<script>
    document.querySelectorAll('.mc-option input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.mc-option').forEach(function(opt) {
                opt.classList.remove('selected');
            });
            this.closest('.mc-option').classList.add('selected');
        });
    });
</script>

</body>
</html>
