<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id']) || $_SESSION['session_level'] !== 'Foititis') {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$user_id = intval($_SESSION['session_id']);

$email      = trim($_POST['email'] ?? '');
$thlefwno   = trim($_POST['thlefwno'] ?? '');
$diefthinsi = trim($_POST['diefthinsi'] ?? '');
$stathero   = trim($_POST['stathero'] ?? '');

if (!preg_match('/^[0-9]{10}$/', $thlefwno)) {
    echo json_encode(['status'=>'error','message'=>'Το κινητό πρέπει να έχει 10 ψηφία.']);
    exit;
}

if ($stathero !== '' && !preg_match('/^[0-9]{10}$/', $stathero)) {
    echo json_encode(['status'=>'error','message'=>'Το σταθερό πρέπει να έχει 10 ψηφία.']);
    exit;
}

$stmt = $mysql_link->prepare("
    UPDATE xrhstes x
    JOIN mathites m ON x.xrhstes_id = m.xrhstes_id
    SET x.email = ?, m.thlefwno = ?, m.diefthinsi = ?, m.stathero = ?
    WHERE x.xrhstes_id = ?
");
$stmt->bind_param("ssssi", $email, $thlefwno, $diefthinsi, $stathero, $user_id);

if ($stmt->execute()) {
    echo json_encode(['status'=>'success','message'=>'Τα στοιχεία ενημερώθηκαν.']);
} else {
    echo json_encode(['status'=>'error','message'=>'Σφάλμα ενημέρωσης.']);
}
$stmt->close();
