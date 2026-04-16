<?php
/* ================================
   DEBUG (βγάλ’ τα αφού δουλέψει)
   ================================ */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ================================
   INIT
   ================================ */
session_start();
require_once('../../login/dbconnection.php');

header('Content-Type: application/json; charset=utf-8');

/* ================================
   AUTH
   ================================ */
if (!isset($_SESSION['session_id'])) {
    http_response_code(401);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

/* ================================
   INPUT
   ================================ */
$topic_id    = intval($_POST['topic_id'] ?? 0);
$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($topic_id <= 0 || $title === '' || $description === '') {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Λάθος δεδομένα'
    ]);
    exit;
}

/* ================================
   FIND KATHIGITIS
   ================================ */
$xrhstes_id = $_SESSION['session_id'];

$stmt = $mysql_link->prepare(
    "SELECT kathigites_id
     FROM kathigites
     WHERE xrhstes_id = ?"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Prepare error'
    ]);
    exit;
}

$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$res) {
    http_response_code(403);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Δεν βρέθηκε καθηγητής'
    ]);
    exit;
}

$kathigites_id = $res['kathigites_id'];

/* ================================
   UPDATE (ΧΩΡΙΣ ΚΑΤΑΣΤΑΣΗ)
   ================================ */
$stmt = $mysql_link->prepare(
    "UPDATE diplwmatikh
     SET thema = ?, perigrafh = ?
     WHERE diplwmatikh_id = ?
       AND epivlepwn_id = ?"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Prepare update error'
    ]);
    exit;
}

$stmt->bind_param(
    "ssii",          // ⬅️ ΠΡΟΣΟΧΗ: 2 strings + 2 ints
    $title,
    $description,
    $topic_id,
    $kathigites_id
);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode([
    'status'     => 'error',
    'mysql_err' => $stmt->error,
    'affected'  => $stmt->affected_rows,
    'topic_id'  => $topic_id,
    'kath_id'   => $kathigites_id
]);
exit;

}

$stmt->close();

/* ================================
   SUCCESS
   ================================ */
echo json_encode([
    'status' => 'success'
]);
exit;
