<?php
require_once __DIR__ . '/db.php';

function haal_level_van_gebruiker(int $user_id): int {
    $stmt = get_db()->prepare('SELECT level FROM gebruikers WHERE user_id = ? LIMIT 1');
    $stmt->execute([$user_id]);
    $level = (int)$stmt->fetchColumn();

    if ($level < 1) {
        $level = 1;
    }
    if ($level > 5) {
        $level = 5;
    }

    return $level;
}

function haal_vragen_van_level(int $level): array {
    static $cache = [];

    if (isset($cache[$level])) {
        return $cache[$level];
    }

    $pdo = get_db();

    $vraag_stmt = $pdo->prepare(
        'SELECT question_id, question_text, xp_reward
         FROM vragen
         WHERE level_id = ?
         ORDER BY question_id'
    );
    $vraag_stmt->execute([$level]);
    $vragen_rows = $vraag_stmt->fetchAll();

    if (empty($vragen_rows)) {
        $cache[$level] = [];
        return $cache[$level];
    }

    $ids = implode(',', array_map('intval', array_column($vragen_rows, 'question_id')));

    $ant_stmt = $pdo->query(
        "SELECT question_id, answer_text, is_correct
         FROM antwoordopties
         WHERE question_id IN ($ids)"
    );
    $alle_antwoorden = $ant_stmt->fetchAll();

    $ant_per_vraag = [];
    foreach ($alle_antwoorden as $a) {
        $ant_per_vraag[(int)$a['question_id']][] = $a;
    }

    $cache[$level] = [];
    foreach ($vragen_rows as $rij) {
        $ants = $ant_per_vraag[(int)$rij['question_id']] ?? [];
        shuffle($ants);

        $keuzes = array_column($ants, 'answer_text');
        $goed   = '';

        foreach ($ants as $a) {
            if ((int)$a['is_correct'] === 1) {
                $goed = $a['answer_text'];
                break;
            }
        }

        $cache[$level][] = [
            'id'         => (int)$rij['question_id'],
            'vraag'      => $rij['question_text'],
            'antwoorden' => $keuzes,
            'goed'       => $goed,
            'xp_reward'  => (int)$rij['xp_reward'],
        ];
    }

    return $cache[$level];
}

function haal_vraag_op_van_level(int $level, int $index): ?array {
    $vragen = haal_vragen_van_level($level);
    return $vragen[$index] ?? null;
}

function geef_totaal_vragen_van_level(int $level): int {
    $stmt = get_db()->prepare('SELECT COUNT(*) FROM vragen WHERE level_id = ?');
    $stmt->execute([$level]);
    return (int)$stmt->fetchColumn();
}

function sla_score_op(?int $user_id, int $score, int $totaal): void {
    $pdo  = get_db();
    $stmt = $pdo->prepare('INSERT INTO scores (user_id, score, totaal) VALUES (?, ?, ?)');
    $stmt->execute([$user_id, $score, $totaal]);
}

function update_level_na_ronde(int $user_id, int $score, int $totaal): void {
    $pdo = get_db();

    $stmt = $pdo->prepare('SELECT xp, level FROM gebruikers WHERE user_id = ? LIMIT 1');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        return;
    }

    $xp = (int)$user['xp'];
    $level = (int)$user['level'];

    if ($score >= $totaal && $level < 5) {
        $level++;
    }

    $xp += ($score * 10);

    $upd = $pdo->prepare('UPDATE gebruikers SET xp = ?, level = ? WHERE user_id = ?');
    $upd->execute([$xp, $level, $user_id]);
}  

function toon_feedback(bool $isGoed, string $goedAntwoord = ''): string {
    if ($isGoed) {
        return '<div class="feedback good">Goed gedaan!</div>';
    }

    $goedAntwoord = htmlspecialchars($goedAntwoord, ENT_QUOTES, 'UTF-8');

    return '<div class="feedback wrong">
        Helaas, fout antwoord.<br>
        <strong>Juiste antwoord:</strong> ' . $goedAntwoord . '
    </div>';
}

function genereer_antwoorden(array $keuzes, string $geselecteerd = ''): string {
    $html = '<div class="mc-grid">';

    foreach ($keuzes as $keuze) {
        $safe    = htmlspecialchars($keuze, ENT_QUOTES, 'UTF-8');
        $cls     = ($geselecteerd === $keuze) ? ' selected' : '';
        $checked = ($geselecteerd === $keuze) ? 'checked' : '';

        $html .= '<label class="mc-option' . $cls . '">';
        $html .= '<input type="radio" name="answer" value="' . $safe . '" ' . $checked . '>';
        $html .= '<span class="mc-label">' . $safe . '</span>';
        $html .= '</label>';
    }

    $html .= '</div>';
    return $html;
}