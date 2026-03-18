<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.html');
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$level   = haal_level_van_gebruiker($user_id);
$total   = geef_totaal_vragen_van_level($level);

if (
    !isset($_SESSION['q_level']) ||
    $_SESSION['q_level'] !== $level ||
    !isset($_SESSION['q_vragen']) ||
    !is_array($_SESSION['q_vragen'])
) {
    $vragen = haal_vragen_van_level($level);

    if (empty($vragen)) {
        die("Geen vragen gevonden voor level " . $level);
    }

    $_SESSION['q_level']    = $level;
    $_SESSION['q_vragen']   = $vragen;
    $_SESSION['q_index']    = 0;
    $_SESSION['q_score']    = 0;
    $_SESSION['q_wrong']    = [];
    $_SESSION['q_phase']    = 'main';
    $_SESSION['q_answered'] = 0;
}

$feedback = '';
$selected = '';
$answered = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['volgende'])) {
        $_SESSION['q_index']++;

        $fase_totaal = ($_SESSION['q_phase'] === 'main')
            ? count($_SESSION['q_vragen'] ?? [])
            : count($_SESSION['q_wrong'] ?? []);

        if ((int)$_SESSION['q_index'] >= $fase_totaal) {
            if ($_SESSION['q_phase'] === 'main' && !empty($_SESSION['q_wrong'])) {
                $_SESSION['q_phase'] = 'retry';
                $_SESSION['q_index'] = 0;
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $score = min((int)$_SESSION['q_score'], $total);
                $uid   = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

                sla_score_op($uid, $score, $total);

                if ($uid !== null) {
                    update_level_na_ronde($uid, $score, $total);
                }

                $_SESSION['q_index']    = 0;
                $_SESSION['q_score']    = 0;
                $_SESSION['q_wrong']    = [];
                $_SESSION['q_phase']    = 'main';
                $_SESSION['q_answered'] = 0;
                unset($_SESSION['q_vragen'], $_SESSION['q_level']);

                header('Location: ../pages/score.html?score=' . $score . '&total=' . $total);
                exit();
            }
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['answer'])) {
        $answer   = trim($_POST['answer']);
        $selected = $answer;

        $vraag_idx = ($_SESSION['q_phase'] === 'retry')
            ? (int)($_SESSION['q_wrong'][(int)$_SESSION['q_index']] ?? 0)
            : (int)$_SESSION['q_index'];

        $vd = $_SESSION['q_vragen'][$vraag_idx] ?? null;

        if ($vd === null) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }

        $isGoed = ($answer === $vd['goed']);

        $_SESSION['q_answered']++;

        if ($isGoed) {
            $_SESSION['q_score']++;
        } elseif ($_SESSION['q_phase'] === 'main') {
            $_SESSION['q_wrong'][] = (int)$_SESSION['q_index'];
        }

        $feedback = toon_feedback($isGoed, $vd['goed']);
        $answered = true;
    }
}

$index    = (int)($_SESSION['q_index'] ?? 0);
$is_retry = (($_SESSION['q_phase'] ?? 'main') === 'retry');

$fase_totaal = $is_retry
    ? count($_SESSION['q_wrong'] ?? [])
    : count($_SESSION['q_vragen'] ?? []);

$vraag_idx = $is_retry
    ? (int)($_SESSION['q_wrong'][$index] ?? 0)
    : $index;

$vraagdata = $_SESSION['q_vragen'][$vraag_idx] ?? null;

if ($vraagdata === null) {
    $score = min((int)($_SESSION['q_score'] ?? 0), $total);
    $uid   = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

    sla_score_op($uid, $score, $total);

    if ($uid !== null) {
        update_level_na_ronde($uid, $score, $total);
    }

    $_SESSION['q_index']    = 0;
    $_SESSION['q_score']    = 0;
    $_SESSION['q_wrong']    = [];
    $_SESSION['q_phase']    = 'main';
    $_SESSION['q_answered'] = 0;
    unset($_SESSION['q_vragen'], $_SESSION['q_level']);

    header('Location: ../pages/score.html?score=' . $score . '&total=' . $total);
    exit();
}

$fill_pct = (int)round((1 - $index / max($fase_totaal, 1)) * 100);

$answered_total = (int)($_SESSION['q_answered'] ?? 0);
$accuracy = $answered_total > 0
    ? min(100, (int)round(($_SESSION['q_score'] ?? 0) / $answered_total * 100))
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
    <a class="topbar-logo">Taaltrainer</a>
    <a href="../pages/dashboard.php" class="topbar-btn">🏠 Dashboard</a>
</nav>

<div class="page">
    <div class="card">

        <?php if ($is_retry): ?>
        <div style="background:#fff3cd; border:1px solid #ffc107; border-radius:8px; padding:8px 14px; margin-bottom:10px; font-size:0.9em; color:#856404;">
            🔁 <strong>Herhaling!</strong> Je had <?php echo count($_SESSION['q_wrong'] ?? []); ?> fout — oefen ze nog een keer.
        </div>
        <?php endif; ?>

        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
            <span style="font-weight:bold; color:#5c3d99;">
                <?php echo $is_retry ? '🔁' : '⭐'; ?>
                Level <?php echo (int)$level; ?> • Vraag <?php echo $index + 1; ?> van <?php echo $fase_totaal; ?>
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