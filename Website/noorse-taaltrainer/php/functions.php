<?php
require_once __DIR__ . '/db.php';

function haal_alle_vragen(): array {
    static $cache = null;
    if ($cache !== null) return $cache;

    $pdo = get_db();

    $vraag_stmt  = $pdo->query('SELECT question_id, question_text, xp_reward FROM vragen ORDER BY question_id');
    $vragen_rows = $vraag_stmt->fetchAll();

    if (empty($vragen_rows)) { $cache = []; return $cache; }

    $ids = implode(',', array_map('intval', array_column($vragen_rows, 'question_id')));
    $ant_stmt = $pdo->query(
        "SELECT question_id, answer_text, is_correct FROM antwoordopties WHERE question_id IN ($ids)"
    );
    $alle_antwoorden = $ant_stmt->fetchAll();

    $ant_per_vraag = [];
    foreach ($alle_antwoorden as $a) {
        $ant_per_vraag[(int)$a['question_id']][] = $a;
    }

    $cache = [];
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

        $cache[] = [
            'id'         => (int)$rij['question_id'],
            'vraag'      => $rij['question_text'],
            'antwoorden' => $keuzes,
            'goed'       => $goed,
            'xp_reward'  => (int)$rij['xp_reward'],
        ];
    }

    return $cache;
}

function haal_vraag_op(int $index): ?array {
    $vragen = haal_alle_vragen();
    return $vragen[$index] ?? null;
}

function geef_totaal_vragen(): int {
    return (int)get_db()->query('SELECT COUNT(*) FROM vragen')->fetchColumn();
}

function sla_score_op(?int $user_id, int $score, int $totaal): void {
    $pdo  = get_db();
    $stmt = $pdo->prepare('INSERT INTO scores (user_id, score, totaal) VALUES (?, ?, ?)');
    $stmt->execute([$user_id, $score, $totaal]);
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