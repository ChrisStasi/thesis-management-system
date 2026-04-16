<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id']) || $_SESSION['session_level'] !== 'Foititis') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Μη εξουσιοδοτημένη πρόσβαση.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$kathigitis_id = intval($data['kathigitis_id'] ?? 0);

if (!$kathigitis_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Μη έγκυρος καθηγητής.']);
    exit;
}

$user_id = intval($_SESSION['session_id']);

/* Βρες διπλωματική + κατάσταση */
$stmt = $mysql_link->prepare("
    SELECT d.diplwmatikh_id, d.katastash
    FROM diplwmatikh d
    JOIN mathites m ON d.mathites_id = m.mathites_id
    WHERE m.xrhstes_id = ?
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$dipl = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$dipl) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Δεν βρέθηκε διπλωματική εργασία.']);
    exit;
}

/* BLOCK όταν είναι Ενεργή */
if ($dipl['katastash'] === 'Ενεργή') {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Η διπλωματική είναι ήδη Ενεργή. Δεν μπορούν να σταλούν νέες προσκλήσεις.'
    ]);
    exit;
}

/* Insert πρόσκληση */
$stmt = $mysql_link->prepare("
    INSERT INTO prosklhsh (diplwmatikh_id, kathigitis_id)
    VALUES (?, ?)
");
$stmt->bind_param("ii", $dipl['diplwmatikh_id'], $kathigitis_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Η πρόσκληση στάλθηκε επιτυχώς.']);
} else {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => 'Ο καθηγητής έχει ήδη προσκληθεί.']);
}

$stmt->close();
$mysql_link->close();
