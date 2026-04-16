<?php
session_start();
require_once('../../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$diplwmatikh_id = (int)$data['diplwmatikh_id'];
$poiotita   = (float)$data['poiotita'];
$xronos     = (float)$data['xronos'];
$keimeno    = (float)$data['keimeno'];
$parousiasi = (float)$data['parousiasi'];

$xrhstes_id = $_SESSION['session_id'];

/* kathigites_id */
$stmt = $mysql_link->prepare("
  SELECT kathigites_id FROM kathigites WHERE xrhstes_id = ?
");
$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(['error' => 'not_professor']);
    exit;
}

$kathigitis_id = (int)$row['kathigites_id'];

/* Έλεγχος αν υπάρχει ήδη βαθμός */
$stmt = $mysql_link->prepare("
  SELECT 1 FROM vathmos
  WHERE diplwmatikh_id = ? AND kathigitis_id = ?
");
$stmt->bind_param("ii", $diplwmatikh_id, $kathigitis_id);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['error' => 'already_graded']);
    exit;
}
$stmt->close();

/* Τελικός βαθμός (σταθμισμένος) */
$telikos =
    $poiotita * 0.60 +
    $xronos * 0.15 +
    $keimeno * 0.15 +
    $parousiasi * 0.10;

/* Αποθήκευση */
$stmt = $mysql_link->prepare("
  INSERT INTO vathmos
  (diplwmatikh_id, kathigitis_id, poiotita, xronos, keimeno, parousiasi, telikos_vathmos)
  VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
  "iiddddd",
  $diplwmatikh_id,
  $kathigitis_id,
  $poiotita,
  $xronos,
  $keimeno,
  $parousiasi,
  $telikos
);

$stmt->execute();
$stmt->close();

echo json_encode(['success' => true]);
