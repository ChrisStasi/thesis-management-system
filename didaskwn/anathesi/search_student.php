<?php
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

/* ======================
   Έλεγχος input
   ====================== */
if (!isset($_POST['search_term']) || trim($_POST['search_term']) === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Δεν δόθηκε όρος αναζήτησης'
    ]);
    exit;
}

$term = '%' . trim($_POST['search_term']) . '%';

/* ======================
   Αναζήτηση φοιτητή
   ====================== */
$stmt = $mysql_link->prepare("
    SELECT m.mathites_id, m.am, x.Plhres_onoma
    FROM mathites m
    JOIN xrhstes x ON m.xrhstes_id = x.xrhstes_id
    WHERE m.am LIKE ?
       OR x.Plhres_onoma LIKE ?
    LIMIT 1
");
$stmt->bind_param("ss", $term, $term);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Δεν βρέθηκε φοιτητής'
    ]);
    exit;
}

/* ======================
   Έλεγχος ΕΝΕΡΓΗΣ διπλωματικής
   ====================== */
$stmt = $mysql_link->prepare("
    SELECT diplwmatikh_id, thema, katastash
    FROM diplwmatikh
    WHERE mathites_id = ?
      AND katastash = 'Ενεργή'
    LIMIT 1
");
$stmt->bind_param("i", $student['mathites_id']);
$stmt->execute();
$activeTopic = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* ======================
   Έλεγχος ΥΠΟ ΕΞΕΤΑΣΗ διπλωματικής
   ====================== */
$stmt = $mysql_link->prepare("
    SELECT diplwmatikh_id, thema, katastash
    FROM diplwmatikh
    WHERE mathites_id = ?
      AND katastash = 'Υπό εξέταση'
    LIMIT 1
");
$stmt->bind_param("i", $student['mathites_id']);
$stmt->execute();
$underReviewTopic = $stmt->get_result()->fetch_assoc();
$stmt->close();


/* ======================
   Απάντηση
   ====================== */
echo json_encode([
    'status' => 'success',
    'student' => $student,

    'has_active_topic' => $activeTopic ? true : false,
    'active_topic' => $activeTopic,

    'has_under_review_topic' => $underReviewTopic ? true : false,
    'under_review_topic' => $underReviewTopic
]);



