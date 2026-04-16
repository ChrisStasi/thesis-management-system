<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['success'=>false]);
    exit;
}

$user_id = $_SESSION['session_id'];

$stmt = $mysql_link->prepare("
SELECT d.diplwmatikh_id, d.thema, d.katastash,
       xs.Plhres_onoma AS epivlepwn
FROM diplwmatikh d
JOIN mathites m ON d.mathites_id = m.mathites_id
JOIN kathigites ks ON d.epivlepwn_id = ks.kathigites_id
JOIN xrhstes xs ON ks.xrhstes_id = xs.xrhstes_id
WHERE m.xrhstes_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$thesis = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$thesis) {
    echo json_encode(['success'=>true,'thesis'=>null]);
    exit;
}

$stmt = $mysql_link->prepare("
SELECT x.Plhres_onoma, p.apanthsh
FROM prosklhsh p
JOIN kathigites k ON p.kathigitis_id = k.kathigites_id
JOIN xrhstes x ON k.xrhstes_id = x.xrhstes_id
WHERE p.diplwmatikh_id = ?
");
$stmt->bind_param("i", $thesis['diplwmatikh_id']);
$stmt->execute();
$invitations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode([
    'success'=>true,
    'thesis'=>$thesis,
    'invitations'=>$invitations
]);
