<?php
session_start();
require_once('../../login/dbconnection.php');

header('Content-Type: application/json');

// ===============================
// ΑΣΦΑΛΕΙΑ
// ===============================
if (!isset($_SESSION['session_id']) || $_SESSION['session_level'] !== 'Foititis') {
    echo json_encode(['success' => false, 'error' => 'unauthorized']);
    exit;
}

if (!isset($_POST['diplwmatikh_id'], $_FILES['file'])) {
    echo json_encode(['success' => false, 'error' => 'missing data']);
    exit;
}

$diplwmatikh_id = (int)$_POST['diplwmatikh_id'];
$file = $_FILES['file'];

// ===============================
// ΕΛΕΓΧΟΣ ΑΡΧΕΙΟΥ
// ===============================
$allowedExtensions = ['pdf', 'doc', 'docx'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExtensions)) {
    echo json_encode([
        'success' => false,
        'error' => 'Μη επιτρεπτός τύπος αρχείου'
    ]);
    exit;
}

// ===============================
// ΦΑΚΕΛΟΣ UPLOADS
// ===============================
$uploadDir = __DIR__ . '/../uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// ===============================
// ΑΠΟΘΗΚΕΥΣΗ ΑΡΧΕΙΟΥ
// ===============================
$filename = uniqid('draft_', true) . '.' . $ext;
$targetPath = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode([
        'success' => false,
        'error' => 'Αποτυχία αποθήκευσης αρχείου'
    ]);
    exit;
}

// ===============================
// ΕΝΗΜΕΡΩΣΗ ΒΑΣΗΣ
// ===============================
$stmt = $mysql_link->prepare("
    UPDATE diplwmatikh
    SET proxeiro_arxeio = ?
    WHERE diplwmatikh_id = ?
");

$stmt->bind_param("si", $filename, $diplwmatikh_id);
$stmt->execute();
$stmt->close();

// ===============================
// ΕΠΙΤΥΧΙΑ
// ===============================
echo json_encode([
    'success' => true,
    'filename' => $filename
]);

