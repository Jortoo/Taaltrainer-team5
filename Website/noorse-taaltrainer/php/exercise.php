<?php
session_start();
require_once 'functions.php';

$total = geef_totaal_vragen();

// Initialize session
if (!isset($_SESSION['q_index']))    $_SESSION['q_index']    = 0;
if (!isset($_SESSION['q_score']))    $_SESSION['q_score']    = 0;
if (!isset($_SESSION['q_wrong']))    $_SESSION['q_wrong']    = [];   // indices of wrong answers (main phase)
if (!isset($_SESSION['q_phase']))    $_SESSION['q_phase']    = 'main'; // 'main' or 'retry'
if (!isset($_SESSION['q_answered'])) $_SESSION['q_answered'] = 0;    // total questions answered

$feedback = '';
$selected = '';
$answered = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['volgende'])) {
        $_SESSION['q_index']++;
        $fase_totaal = ($_SESSION['q_phase'] === 'main') ? $total : count($_SESSION['q_wrong']);

        if ((int)$_SESSION['q_index'] >= $fase_totaal) {
            if ($_SESSION['q_phase'] === 'main' && !empty($_SESSION['q_wrong'])) {
                // Switch to retry phase for wrong answers
                $_SESSION['q_phase'] = 'retry';
                $_SESSION['q_index'] = 0;
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            } else {
                // All done – score is capped at original total
                $score = min((int)$_SESSION['q_score'], $total);
                $uid   = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
                sla_score_op($uid, $score, $total);
                $_SESSION['q_index']    = 0;
                $_SESSION['q_score']    = 0;
                $_SESSION['q_wrong']    = [];
                $_SESSION['q_phase']    = 'main';
                $_SESSION['q_answered'] = 0;
                header('Location: ../pages/score.html?score=' . $score . '&total=' . $total);
                exit();
            }
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['answer'])) {
        $answer   = htmlspecialchars(trim($_POST['answer']), ENT_QUOTES, 'UTF-8');
        $selected = $answer;

        // Determine which question index to look up
        $vraag_idx = ($_SESSION['q_phase'] === 'retry')
            ? (int)$_SESSION['q_wrong'][(int)$_SESSION['q_index']]
            : (int)$_SESSION['q_index'];

        $vd     = haal_vraag_op($vraag_idx);
        $isGoed = ($answer === $vd['goed']);

        $_SESSION['q_answered']++;
        if ($isGoed) {
            $_SESSION['q_score']++;
        } elseif ($_SESSION['q_phase'] === 'main') {
            // Queue this question for retry
            $_SESSION['q_wrong'][] = (int)$_SESSION['q_index'];
        }

        $feedback = toon_feedback($isGoed);
        $answered = true;
    }
}

$index    = (int)$_SESSION['q_index'];
$is_retry = ($_SESSION['q_phase'] === 'retry');

$fase_totaal = $is_retry ? count($_SESSION['q_wrong']) : $total;
$vraag_idx   = $is_retry ? (int)($_SESSION['q_wrong'][$index] ?? 0) : $index;
$vraagdata   = haal_vraag_op($vraag_idx);

$fill_pct = (int)round((1 - $index / max($fase_totaal, 1)) * 100);

// Accuracy capped at 100%
$answered_total = (int)$_SESSION['q_answered'];
$accuracy = $answered_total > 0
    ? min(100, (int)round($_SESSION['q_score'] / $answered_total * 100))
    : 0;
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

        <?php if ($is_retry): ?>
        <div style="background:#fff3cd; border:1px solid #ffc107; border-radius:8px; padding:8px 14px; margin-bottom:10px; font-size:0.9em; color:#856404;">
            🔁 <strong>Herhaling!</strong> Je had <?php echo count($_SESSION['q_wrong']); ?> fout — oefen ze nog een keer.
        </div>
        <?php endif; ?>

        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
            <span style="font-weight:bold; color:#5c3d99;">
                <?php echo $is_retry ? '🔁' : '⭐'; ?>
                Vraag <?php echo $index + 1; ?> van <?php echo $fase_totaal; ?>
            </span>
            <span style="color:#888; font-size:0.9em;"><?php echo $accuracy; ?>% goed</span>
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
                    <?php echo ($index + 1 >= $fase_totaal) ? '🏆 Bekijk resultaten' : '➡️ Volgende vraag'; ?>
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
