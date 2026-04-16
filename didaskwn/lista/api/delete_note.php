<?php
session_start();
require_once('../../../login/dbconnection.php');

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

/* =========================
   ΕΛΕΓΧΟΙ
========================= */
if (!isset($_SESSION['session_id'])) {
    echo json_encode(['error' => 'Μη εξουσιοδοτημένη πρόσβαση']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['shmeiwsh_id'])) {
    echo json_encode(['error' => 'Δεν ορίστηκε σημείωση']);
    exit;
}

$shmeiwsh_id = (int)$data['shmeiwsh_id'];
$xrhstes_id = $_SESSION['session_id'];

/* =========================
   ΕΥΡΕΣΗ kathigites_id
========================= */
$stmt = $mysql_link->prepare(
    "SELECT kathigites_id FROM kathigites WHERE xrhstes_id = ?"
);
$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(['error' => 'Ο χρήστης δεν είναι διδάσκων']);
    exit;
}

$kathigites_id = (int)$row['kathigites_id'];

/* =========================
   ΔΙΑΓΡΑΦΗ (ΜΟΝΟ ΑΝ ΤΗΝ ΕΧΕΙ ΓΡΑΨΕΙ)
========================= */
$stmt = $mysql_link->prepare("
    DELETE FROM shmeiwseis
    WHERE shmeiwsh_id = ?
      AND kathigiths_id = ?
");
$stmt->bind_param("ii", $shmeiwsh_id, $kathigites_id);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    echo json_encode(['error' => 'Δεν έχετε δικαίωμα διαγραφής']);
    $stmt->close();
    exit;
}

$stmt->close();

echo json_encode(['success' => true]);
