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

if (!isset($data['diplwmatikh_id'])) {
    echo json_encode(['error' => 'Δεν ορίστηκε διπλωματική']);
    exit;
}

$diplwmatikh_id = (int)$data['diplwmatikh_id'];
$xrhstes_id = $_SESSION['session_id'];

/* =========================
   kathigiths_id
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

$kathigiths_id = (int)$row['kathigites_id'];

/* =========================
   ΕΛΕΓΧΟΣ ΕΠΙΒΛΕΨΗΣ
========================= */
$stmt = $mysql_link->prepare("
    SELECT epivlepwn_id
    FROM diplwmatikh
    WHERE diplwmatikh_id = ?
");
$stmt->bind_param("i", $diplwmatikh_id);
$stmt->execute();
$d = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$d || (int)$d['epivlepwn_id'] !== $kathigiths_id) {
    echo json_encode(['error' => 'Δεν είστε επιβλέπων']);
    exit;
}

/* =========================
   TRANSACTION
========================= */
$mysql_link->begin_transaction();

try {
    // 1️⃣ διαγραφή προσκλήσεων
    $stmt = $mysql_link->prepare(
        "DELETE FROM prosklhsh WHERE diplwmatikh_id = ?"
    );
    $stmt->bind_param("i", $diplwmatikh_id);
    $stmt->execute();
    $stmt->close();

    // 2️⃣ διαγραφή τριμελούς
    $stmt = $mysql_link->prepare(
        "DELETE FROM trimelhs_epitroph WHERE diplwmatikh_id = ?"
    );
    $stmt->bind_param("i", $diplwmatikh_id);
    $stmt->execute();
    $stmt->close();

    // 3️⃣ reset διπλωματικής
    $stmt = $mysql_link->prepare("
        UPDATE diplwmatikh
        SET
          mathites_id = NULL,
          katastash = 'Μη Ανατεθειμένη'
        WHERE diplwmatikh_id = ?
    ");
    $stmt->bind_param("i", $diplwmatikh_id);
    $stmt->execute();
    $stmt->close();

    $mysql_link->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $mysql_link->rollback();
    echo json_encode(['error' => 'Αποτυχία ακύρωσης']);
}

