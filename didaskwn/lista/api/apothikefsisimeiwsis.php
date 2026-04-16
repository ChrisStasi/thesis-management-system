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

if (
    !isset($data['diplwmatikh_id']) ||
    !isset($data['keimeno'])
) {
    echo json_encode(['error' => 'Ελλιπή δεδομένα']);
    exit;
}

$diplwmatikh_id = (int)$data['diplwmatikh_id'];
$keimeno = trim($data['keimeno']);
$xrhstes_id = $_SESSION['session_id'];

if ($keimeno === '' || mb_strlen($keimeno) > 300) {
    echo json_encode(['error' => 'Μη έγκυρο κείμενο']);
    exit;
}

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
   ΑΠΟΘΗΚΕΥΣΗ ΣΗΜΕΙΩΣΗΣ
========================= */
$stmt = $mysql_link->prepare("
    INSERT INTO shmeiwseis
    (diplwmatikh_id, kathigiths_id, keimeno, hmeromhnia)
    VALUES (?, ?, ?, NOW())
");

if (!$stmt) {
    echo json_encode(['error' => $mysql_link->error]);
    exit;
}

$stmt->bind_param(
    "iis",
    $diplwmatikh_id,
    $kathigites_id,   // ⚠️ μπαίνει εδώ παρότι λέγεται αλλιώς στον πίνακα
    $keimeno
);

$stmt->execute();
$stmt->close();

echo json_encode(['success' => true]);
