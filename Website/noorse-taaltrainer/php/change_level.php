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
    
    // Update het niveau in de database
    // Zet ook XP op 0 zodat je opnieuw kunt groeien naar het volgende nivel
    $stmt = $pdo->prepare('UPDATE gebruikers SET level = ?, xp = 0 WHERE user_id = ?');
    $stmt->execute([$nieuw_level, $user_id]);
    
    // Redirect terug naar profiel met succes
    header('Location: ../pages/profile.php?succes=Niveau+gewijzigd!');
    exit();
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    header('Location: ../pages/profile.php?fout=Er+is+een+fout+opgetreden');
    exit();
}
?>
