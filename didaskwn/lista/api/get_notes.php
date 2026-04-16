<?php
session_start();
require_once('../../../login/dbconnection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['error' => 'Μη εξουσιοδοτημένη πρόσβαση']);
    exit;
}

if (!isset($_GET['diplwmatikh_id'])) {
    echo json_encode(['error' => 'Δεν ορίστηκε διπλωματική']);
    exit;
}

$diplwmatikh_id = (int)$_GET['diplwmatikh_id'];

$stmt = $mysql_link->prepare("
    SELECT 
        s.shmeiwsh_id,
        s.keimeno,
        s.hmeromhnia,
        x.Plhres_onoma AS kathigitis
    FROM shmeiwseis s
    JOIN kathigites k ON s.kathigiths_id = k.kathigites_id
    JOIN xrhstes x ON k.xrhstes_id = x.xrhstes_id
    WHERE s.diplwmatikh_id = ?
    ORDER BY s.hmeromhnia DESC
");

$stmt->bind_param("i", $diplwmatikh_id);
$stmt->execute();
$notes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode($notes);
