<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['status'=>'error']);
    exit;
}

$title = trim($_POST['title'] ?? '');
$desc  = trim($_POST['description'] ?? '');

if ($title==='' || $desc==='') {
    echo json_encode(['status'=>'error']);
    exit;
}

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

/* PDF */
$link = '';
if (!empty($_FILES['file']['name'])) {
    $dir = '../../uploads/';
    if (!is_dir($dir)) mkdir($dir,0777,true);
    $link = uniqid().'-'.basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'],$dir.$link);
}

/* INSERT */
$stmt = $mysql_link->prepare("
  INSERT INTO diplwmatikh (thema, perigrafh, link, epivlepwn_id)
  VALUES (?,?,?,?)
");
$stmt->bind_param("sssi",$title,$desc,$link,$kathigites_id);
$stmt->execute();
$stmt->close();

echo json_encode(['status'=>'success']);
