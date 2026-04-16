<?php
session_start();
require_once('../../login/dbconnection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['session_id']) || $_SESSION['session_level'] !== 'Foititis') {
    echo json_encode(['success' => false, 'error' => 'unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$diplwmatikh_id = (int)($data['diplwmatikh_id'] ?? 0);
$hmera_wra      = trim($data['hmera_wra'] ?? '');
$typos          = trim($data['typos'] ?? '');
$topothesia     = trim($data['topothesia'] ?? '');

if (!$diplwmatikh_id || !$hmera_wra || !$typos || !$topothesia) {
    echo json_encode(['success' => false, 'error' => 'missing_fields']);
    exit;
}

/* ===============================
   VALIDATION ΑΝΑ ΤΥΠΟ
   =============================== */

// Διαδικτυακά → ΜΟΝΟ link
if ($typos === 'Διαδικτυακά') {
    if (!filter_var($topothesia, FILTER_VALIDATE_URL)) {
        echo json_encode([
            'success' => false,
            'error'   => 'invalid_link'
        ]);
        exit;
    }
}

// Δια ζώσης → ΜΟΝΟ αίθουσα (ΟΧΙ URL)
if ($typos === 'Δια ζώσης') {
    if (filter_var($topothesia, FILTER_VALIDATE_URL)) {
        echo json_encode([
            'success' => false,
            'error'   => 'invalid_room'
        ]);
        exit;
    }
}

/* ===============================
   UPDATE (ΔΕΝ ΜΗΔΕΝΙΖΟΥΜΕ ΤΙΠΟΤΑ)
   =============================== */
$stmt = $mysql_link->prepare("
    UPDATE diplwmatikh
    SET 
        hmera_wra_parousiashs = ?,
        typos_parousiashs     = ?,
        topothesia_parousiashs = ?
    WHERE diplwmatikh_id = ?
");

$stmt->bind_param(
    "sssi",
    $hmera_wra,
    $typos,
    $topothesia,
    $diplwmatikh_id
);

$ok = $stmt->execute();
$stmt->close();

echo json_encode(['success' => $ok]);
