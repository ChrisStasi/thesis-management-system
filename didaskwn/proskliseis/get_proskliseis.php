<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['status'=>'error']);
    exit;
}

$xrhstes_id = intval($_SESSION['session_id']);

/* kathigites_id */
$stmt = $mysql_link->prepare(
    "SELECT kathigites_id FROM kathigites WHERE xrhstes_id=?"
);
$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$kathigitis_id = $stmt->get_result()->fetch_assoc()['kathigites_id'];
$stmt->close();

/* προσκλήσεις */
$stmt = $mysql_link->prepare(
    "SELECT 
        p.diplwmatikh_id,
        d.thema,
        d.perigrafh,
        p.hmeromhnia_prosklhshs,
        xs.Plhres_onoma AS student_name,
        xk.Plhres_onoma AS supervisor_name
     FROM prosklhsh p
     JOIN diplwmatikh d ON p.diplwmatikh_id=d.diplwmatikh_id
     JOIN mathites m ON d.mathites_id=m.mathites_id
     JOIN xrhstes xs ON m.xrhstes_id=xs.xrhstes_id
     JOIN kathigites k ON d.epivlepwn_id=k.kathigites_id
     JOIN xrhstes xk ON k.xrhstes_id=xk.xrhstes_id
     WHERE p.kathigitis_id=? AND p.apanthsh IS NULL"
);
$stmt->bind_param("i", $kathigitis_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode(['status'=>'success','data'=>$data]);
