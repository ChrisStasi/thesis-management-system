<?php
session_start();
require_once('../../../login/dbconnection.php');

if (!isset($_SESSION['session_id'])) {
    http_response_code(401);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$diplwmatikh_id = (int)$data['diplwmatikh_id'];
$gs_arithmos = (int)$data['gs_arithmos'];
$gs_etos = (int)$data['gs_etos'];

$xrhstes_id = $_SESSION['session_id'];

/* Έλεγχος ότι είναι επιβλέπων */
$stmt = $mysql_link->prepare("
    SELECT d.anatethike
    FROM diplwmatikh d
    JOIN kathigites k ON d.epivlepwn_id = k.kathigites_id
    WHERE d.diplwmatikh_id = ?
      AND k.xrhstes_id = ?
      AND d.katastash = 'Ενεργή'
");
$stmt->bind_param("ii", $diplwmatikh_id, $xrhstes_id);
$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['error' => 'Μη επιτρεπτή ενέργεια']);
    exit;
}

$row = $res->fetch_assoc();

/* Έλεγχος 2ετίας */
$assignmentDate = new DateTime($row['anatethike']);
$today = new DateTime();

if ($assignmentDate->diff($today)->y < 2) {
    echo json_encode([
        'error' => 'Δεν έχουν παρέλθει δύο έτη από την οριστική ανάθεση'
    ]);
    exit;
}

/* Ακύρωση */
$update = $mysql_link->prepare("
    UPDATE diplwmatikh
    SET katastash = 'Μη Ανατεθειμένη'
    WHERE diplwmatikh_id = ?
");
$update->bind_param("i", $diplwmatikh_id);
$update->execute();

echo json_encode(['success' => true]);
