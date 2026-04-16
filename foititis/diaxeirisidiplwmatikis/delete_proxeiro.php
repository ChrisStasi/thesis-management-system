<?php
session_start();
require_once('../../login/dbconnection.php');

if (!isset($_SESSION['session_id']) || $_SESSION['session_level'] !== 'Foititis') {
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['diplwmatikh_id'])) {
    echo json_encode(['error' => 'missing data']);
    exit;
}

$diplwmatikh_id = (int)$data['diplwmatikh_id'];

/* Φέρνουμε το αρχείο */
$stmt = $mysql_link->prepare("
    SELECT proxeiro_arxeio
    FROM diplwmatikh
    WHERE diplwmatikh_id = ?
");
$stmt->bind_param("i", $diplwmatikh_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* Διαγραφή αρχείου από δίσκο */
if ($row && $row['proxeiro_arxeio']) {
    $path = __DIR__ . "/../uploads/" . $row['proxeiro_arxeio'];
    if (file_exists($path)) {
        unlink($path);
    }
}

/* Καθαρισμός πεδίου στη ΒΔ */
$stmt = $mysql_link->prepare("
    UPDATE diplwmatikh
    SET proxeiro_arxeio = NULL
    WHERE diplwmatikh_id = ?
");
$stmt->bind_param("i", $diplwmatikh_id);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true]);
