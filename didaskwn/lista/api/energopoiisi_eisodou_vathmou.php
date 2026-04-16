<?php
session_start();
require_once('../../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$diplwmatikh_id = (int)($data['diplwmatikh_id'] ?? 0);
$xrhstes_id = $_SESSION['session_id'];

/* kathigites_id */
$stmt = $mysql_link->prepare(
  "SELECT kathigites_id FROM kathigites WHERE xrhstes_id = ?"
);
$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(['error' => 'not_professor']);
    exit;
}

$kathigites_id = $row['kathigites_id'];

/* ΜΟΝΟ επιβλέπων & Υπό Εξέταση */
$stmt = $mysql_link->prepare("
  SELECT 1
  FROM diplwmatikh
  WHERE diplwmatikh_id = ?
    AND epivlepwn_id = ?
    AND katastash = 'Υπό εξέταση'
");
$stmt->bind_param("ii", $diplwmatikh_id, $kathigites_id);
$stmt->execute();

if ($stmt->get_result()->num_rows === 0) {
    echo json_encode(['error' => 'not_allowed']);
    exit;
}
$stmt->close();

/* Ενεργοποίηση */
$stmt = $mysql_link->prepare("
  UPDATE diplwmatikh
  SET energopoihsh_eisodou_vathmou = 1
  WHERE diplwmatikh_id = ?
");
$stmt->bind_param("i", $diplwmatikh_id);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true]);
