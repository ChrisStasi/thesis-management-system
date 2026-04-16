<?php
session_start();
require_once('../../login/dbconnection.php');

header('Content-Type: application/json; charset=utf-8');

/* ======================================================
   Έλεγχος session
   ====================================================== */
if (!isset($_SESSION['session_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Μη εξουσιοδοτημένη πρόσβαση'
    ]);
    exit;
}

/* ======================================================
   Έλεγχος input
   ====================================================== */
$topic_id   = (int)($_POST['topic_id'] ?? 0);
$student_id = (int)($_POST['student_id'] ?? 0);

if ($topic_id <= 0 || $student_id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Λανθασμένα δεδομένα'
    ]);
    exit;
}

/* ======================================================
   Βρες kathigites_id
   ====================================================== */
$xrhstes_id = (int)$_SESSION['session_id'];

$stmt = $mysql_link->prepare("
    SELECT kathigites_id
    FROM kathigites
    WHERE xrhstes_id = ?
");
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

/* ======================================================
   1️⃣ Έλεγχος: Ο φοιτητής έχει ήδη διπλωματική
   ====================================================== */
$stmt = $mysql_link->prepare("
    SELECT 1
    FROM diplwmatikh
    WHERE mathites_id = ?
      AND katastash IN ('Ενεργή', 'Προσωρινή Κατοχύρωση', 'Υπό Ανάθεση')
");
$stmt->bind_param("i", $student_id);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    $stmt->close();
    echo json_encode([
        'status' => 'error',
        'message' => 'Ο φοιτητής έχει ήδη διπλωματική'
    ]);
    exit;
}
$stmt->close();

/* ======================================================
   2️⃣ Έλεγχος: Το θέμα ανήκει στον διδάσκοντα
   ====================================================== */
$stmt = $mysql_link->prepare("
    SELECT katastash
    FROM diplwmatikh
    WHERE diplwmatikh_id = ?
      AND epivlepwn_id = ?
");
$stmt->bind_param("ii", $topic_id, $kathigites_id);
$stmt->execute();
$topic = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$topic) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Δεν έχετε δικαίωμα για αυτό το θέμα'
    ]);
    exit;
}

/* ======================================================
   3️⃣ Ανάθεση (Προσωρινή Κατοχύρωση)
   ====================================================== */
$stmt = $mysql_link->prepare("
    UPDATE diplwmatikh
    SET
        mathites_id = ?,
        katastash  = 'Προσωρινή Κατοχύρωση',
        anatethike = NOW()
    WHERE diplwmatikh_id = ?
      AND epivlepwn_id = ?
");
$stmt->bind_param("iii", $student_id, $topic_id, $kathigites_id);
$stmt->execute();
$stmt->close();


/* ======================================================
   4️⃣ Αυτόματη εισαγωγή επιβλέποντα στην τριμελή
   ====================================================== */
$stmt = $mysql_link->prepare("
    INSERT IGNORE INTO trimelhs_epitroph
        (diplwmatikh_id, kathigiths_id)
    VALUES (?, ?)
");
$stmt->bind_param("ii", $topic_id, $kathigites_id);
$stmt->execute();
$stmt->close();

/* ======================================================
   OK
   ====================================================== */
echo json_encode([
    'status' => 'success'
]);

