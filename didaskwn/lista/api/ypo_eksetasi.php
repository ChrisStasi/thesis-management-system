<?php
session_start();
require_once('../../../login/dbconnection.php');

$data = json_decode(file_get_contents('php://input'), true);
$diplwmatikh_id = (int)$data['diplwmatikh_id'];
$xrhstes_id = $_SESSION['session_id'];

$stmt = $mysql_link->prepare("
  UPDATE diplwmatikh d
  JOIN kathigites k ON d.epivlepwn_id = k.kathigites_id
  SET d.katastash = 'Υπό Εξέταση'
  WHERE d.diplwmatikh_id = ? AND k.xrhstes_id = ?
");
$stmt->bind_param("ii", $diplwmatikh_id, $xrhstes_id);
$stmt->execute();

echo json_encode(['success' => true]);
