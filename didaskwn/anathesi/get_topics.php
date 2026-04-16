<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json; charset=utf-8');

/* ======================
   Έλεγχος session
   ====================== */
if (!isset($_SESSION['session_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Μη εξουσιοδοτημένη πρόσβαση'
    ]);
    exit;
}

$xrhstes_id = (int)$_SESSION['session_id'];

/* ======================
   Βρες kathigites_id
   ====================== */
$stmt = $mysql_link->prepare(
    "SELECT kathigites_id
     FROM kathigites
     WHERE xrhstes_id = ?"
);
$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$k = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$k) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Ο χρήστης δεν είναι διδάσκων'
    ]);
    exit;
}

$kathigites_id = (int)$k['kathigites_id'];

/* ======================
   Ανάκτηση θεμάτων
   (με στοιχεία φοιτητή)
   ====================== */
$stmt = $mysql_link->prepare("
    SELECT
        d.diplwmatikh_id,
        d.thema,
        d.perigrafh,
        d.katastash,
        d.mathites_id,
        d.dhmiourgithike,
        m.am,
        x.Plhres_onoma AS foititis
    FROM diplwmatikh d
    LEFT JOIN mathites m
        ON d.mathites_id = m.mathites_id
    LEFT JOIN xrhstes x
        ON m.xrhstes_id = x.xrhstes_id
    WHERE d.epivlepwn_id = ?
    ORDER BY d.dhmiourgithike DESC
");

$stmt->bind_param("i", $kathigites_id);
$stmt->execute();

/* ⬅️ ΠΡΩΤΑ result */
$result = $stmt->get_result();
$topics = $result->fetch_all(MYSQLI_ASSOC);

/* ⬅️ ΜΕΤΑ close */
$stmt->close();

/* ======================
   Response
   ====================== */
echo json_encode([
    'status' => 'success',
    'topics' => $topics
]);


