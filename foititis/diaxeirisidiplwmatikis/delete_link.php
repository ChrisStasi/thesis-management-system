<?php
session_start();
require_once('../../login/dbconnection.php');

/* ===============================
   AUTH CHECK
   =============================== */
if (!isset($_SESSION['session_id']) || $_SESSION['session_level'] !== 'Foititis') {
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

/* ===============================
   INPUT CHECK
   =============================== */
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['diplwmatikh_id'])) {
    echo json_encode(['error' => 'missing data']);
    exit;
}

$diplwmatikh_id = (int)$data['diplwmatikh_id'];
$xrhstes_id    = (int)$_SESSION['session_id'];

/* ===============================
   OWNERSHIP CHECK
   =============================== */
/* Ελέγχουμε ότι η διπλωματική ανήκει στον φοιτητή */
$stmt = $mysql_link->prepare("
    SELECT d.diplwmatikh_id
    FROM diplwmatikh d
    JOIN mathites m ON d.mathites_id = m.mathites_id
    WHERE d.diplwmatikh_id = ?
      AND m.xrhstes_id = ?
");
$stmt->bind_param("ii", $diplwmatikh_id, $xrhstes_id);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();

if ($res->num_rows === 0) {
    echo json_encode(['error' => 'not allowed']);
    exit;
}

/* ===============================
   DELETE LINK
   =============================== */
$stmt = $mysql_link->prepare("
    UPDATE diplwmatikh
    SET link = NULL
    WHERE diplwmatikh_id = ?
");
$stmt->bind_param("i", $diplwmatikh_id);
$stmt->execute();
$stmt->close();

/* ===============================
   RESPONSE
   =============================== */
echo json_encode(['success' => true]);
