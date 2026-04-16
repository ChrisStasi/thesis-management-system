<?php
session_start();
require_once('../../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

$diplwmatikh_id = (int)($_GET['diplwmatikh_id'] ?? 0);
if ($diplwmatikh_id <= 0) {
    echo json_encode(['error' => 'invalid_id']);
    exit;
}

/* kathigitis_id του χρήστη */
$stmt = $mysql_link->prepare(
  "SELECT kathigites_id FROM kathigites WHERE xrhstes_id = ?"
);
$stmt->bind_param("i", $_SESSION['session_id']);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

$my_kathigitis_id = $row ? (int)$row['kathigites_id'] : 0;

/* Όλοι οι βαθμοί */
$stmt = $mysql_link->prepare("
  SELECT 
    v.kathigitis_id,
    x.Plhres_onoma AS kathigitis,
    v.poiotita,
    v.xronos,
    v.keimeno,
    v.parousiasi,
    v.telikos_vathmos,
    v.hmeromhnia
  FROM vathmos v
  JOIN kathigites k ON v.kathigitis_id = k.kathigites_id
  JOIN xrhstes x ON k.xrhstes_id = x.xrhstes_id
  WHERE v.diplwmatikh_id = ?
  ORDER BY v.hmeromhnia ASC
");
$stmt->bind_param("i", $diplwmatikh_id);
$stmt->execute();
$grades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

/* Υπολογισμός μέσου όρου */
$avg = null;
if (count($grades) > 0) {
    $sum = array_sum(array_column($grades, 'telikos_vathmos'));
    $avg = round($sum / count($grades), 2);
}

echo json_encode([
    'grades' => $grades,
    'average' => $avg,
    'my_kathigitis_id' => $my_kathigitis_id
]);
