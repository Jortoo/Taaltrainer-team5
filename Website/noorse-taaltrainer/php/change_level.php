<?php
session_start();

// Check of gebruiker ingelogd is
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.html');
    exit();
}

require_once __DIR__ . '/db.php';

$user_id = (int)$_SESSION['user_id'];
$nieuw_level = isset($_POST['nieuw_level']) ? (int)$_POST['nieuw_level'] : null;

// Valideer het nieuwe niveau
if ($nieuw_level === null || $nieuw_level < 1 || $nieuw_level > 5) {
    header('Location: ../pages/profile.php?fout=Ongeldig+niveau');
    exit();
}

try {
    $pdo = get_db();
    
    // Haal huidige XP, level en max_unlocked_level van gebruiker op
    $stmt = $pdo->prepare('SELECT xp, level, max_unlocked_level FROM gebruikers WHERE user_id = ? LIMIT 1');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    $current_xp = (int)$user['xp'];
    $current_level = (int)$user['level'];
    $current_max_unlocked = (int)$user['max_unlocked_level'];
    
    // Definieer kosten per niveau (alleen voor nog niet ontgrendelde levels)
    $level_costs = [1 => 0, 2 => 100, 3 => 200, 4 => 300, 5 => 400];
    $is_unlocked = ($nieuw_level <= $current_max_unlocked);
    $cost = (!$is_unlocked) ? $level_costs[$nieuw_level] : 0;
    
    // Controleer of gebruiker genoeg XP heeft (alleen voor nog niet ontgrendelde levels)
    if (!$is_unlocked && $current_xp < $cost) {
        header('Location: ../pages/profile.php?fout=Niet+genoeg+XP+voor+dit+niveau');
        exit();
    }
    
    // Trek XP af alleen als het een nieuw niveau is dat ontgrendeld wordt
    $new_xp = (!$is_unlocked) ? $current_xp - $cost : $current_xp;
    $new_max_unlocked = max($current_max_unlocked, $nieuw_level);
    
    $stmt = $pdo->prepare('UPDATE gebruikers SET level = ?, xp = ?, max_unlocked_level = ? WHERE user_id = ?');
    $stmt->execute([$nieuw_level, $new_xp, $new_max_unlocked, $user_id]);
    
    // Redirect terug naar profiel met succes
    $message = (!$is_unlocked) ? 'Niveau+ontgrendeld!+' . $cost . '+XP+uitgegeven.' : 'Niveau+gewijzigd!';
    header('Location: ../pages/profile.php?succes=' . $message);
    exit();
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    header('Location: ../pages/profile.php?fout=Er+is+een+fout+opgetreden');
    exit();
}
?>
