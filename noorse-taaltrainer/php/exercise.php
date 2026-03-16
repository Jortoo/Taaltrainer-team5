<?php
// exercise.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = $_POST['answer'];
    // Demo: altijd goed
    header('Location: ../pages/score.html');
    exit();
}
?>