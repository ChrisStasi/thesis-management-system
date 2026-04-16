<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['status'=>'error']);
    exit;
}

$xrhstes_id = $_SESSION['session_id'];

/* 🔹 Βρες kathigites_id */
$stmt = $mysql_link->prepare(
    "SELECT kathigites_id FROM kathigites WHERE xrhstes_id = ?"
);
$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(['status'=>'error','message'=>'Not professor']);
    exit;
}

$kathigites_id = $row['kathigites_id'];

/* 🔹 Φόρτωση θεμάτων */
$stmt = $mysql_link->prepare("
    SELECT diplwmatikh_id, thema, perigrafh
FROM diplwmatikh
WHERE epivlepwn_id = ?
ORDER BY diplwmatikh_id DESC

");
$stmt->bind_param("i", $kathigites_id);
$stmt->execute();

$result = $stmt->get_result();
$topics = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode([
    'status' => 'success',
    'topics' => $topics
]);
