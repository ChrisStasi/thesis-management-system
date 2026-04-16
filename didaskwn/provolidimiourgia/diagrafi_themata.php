<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['status'=>'error']);
    exit;
}

$topic_id = (int)($_POST['topic_id'] ?? 0);

/* kathigites_id */
$xrhstes_id = (int)$_SESSION['session_id'];
$stmt = $mysql_link->prepare(
  "SELECT kathigites_id FROM kathigites WHERE xrhstes_id=?"
);
$stmt->bind_param("i",$xrhstes_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

$kathigites_id = (int)$row['kathigites_id'];

$stmt = $mysql_link->prepare("
  DELETE FROM diplwmatikh
  WHERE diplwmatikh_id=? AND epivlepwn_id=?
");
$stmt->bind_param("ii",$topic_id,$kathigites_id);
$stmt->execute();
$stmt->close();

echo json_encode(['status'=>'success']);


