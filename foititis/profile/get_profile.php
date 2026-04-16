<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id']) || $_SESSION['session_level'] !== 'Foititis') {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$user_id = intval($_SESSION['session_id']);

$stmt = $mysql_link->prepare("
    SELECT x.Plhres_onoma, x.email, m.thlefwno, m.am, m.diefthinsi, m.stathero
    FROM xrhstes x
    JOIN mathites m ON x.xrhstes_id = m.xrhstes_id
    WHERE x.xrhstes_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo json_encode(['status'=>'success','data'=>$data]);
