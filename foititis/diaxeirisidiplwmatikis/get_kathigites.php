<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id']) || $_SESSION['session_level'] !== 'Foititis') {
    http_response_code(401);
    echo json_encode(['success'=>false,'message'=>'Unauthorized']);
    exit;
}

$user_id = intval($_SESSION['session_id']);

/* Βρες διπλωματική + επιβλέποντα + κατάσταση */
$stmt = $mysql_link->prepare("
    SELECT d.diplwmatikh_id, d.epivlepwn_id, d.katastash
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
    echo json_encode(['success'=>true,'lecturers'=>[]]);
    exit;
}

/* Αν είναι Ενεργή -> δεν έχει νόημα λίστα */
if ($dipl['katastash'] === 'Ενεργή') {
    echo json_encode(['success'=>true,'lecturers'=>[]]);
    exit;
}

/* Φέρε καθηγητές εκτός επιβλέποντα και εκτός ήδη προσκεκλημένων */
$stmt = $mysql_link->prepare("
    SELECT k.kathigites_id, x.Plhres_onoma
    FROM kathigites k
    JOIN xrhstes x ON k.xrhstes_id = x.xrhstes_id
    WHERE k.kathigites_id <> ?
      AND k.kathigites_id NOT IN (
          SELECT p.kathigitis_id
          FROM prosklhsh p
          WHERE p.diplwmatikh_id = ?
      )
    ORDER BY x.Plhres_onoma
");
$stmt->bind_param("ii", $dipl['epivlepwn_id'], $dipl['diplwmatikh_id']);
$stmt->execute();
$lecturers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode(['success'=>true,'lecturers'=>$lecturers]);
$mysql_link->close();
