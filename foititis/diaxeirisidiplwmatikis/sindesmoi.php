<?php
session_start();
require_once('../../login/dbconnection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['session_id']) || $_SESSION['session_level'] !== 'Foititis') {
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['diplwmatikh_id'], $data['link'])) {
    echo json_encode(['error' => 'missing data']);
    exit;
}

$diplwmatikh_id = (int)$data['diplwmatikh_id'];
$link = trim($data['link']);

$stmt = $mysql_link->prepare("
    UPDATE diplwmatikh
    SET link = ?
    WHERE diplwmatikh_id = ?
");
$stmt->bind_param("si", $link, $diplwmatikh_id);
$stmt->execute();

echo json_encode(['success' => true]);

