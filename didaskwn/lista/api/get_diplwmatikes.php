<?php
session_start();
require_once('../../../login/dbconnection.php');

if (!isset($_SESSION['session_id'])) {
    http_response_code(401);
    exit;
}

$xrhstes_id = $_SESSION['session_id'];

$stmt = $mysql_link->prepare(
  "SELECT kathigites_id FROM kathigites WHERE xrhstes_id = ?"
);
$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

$kathigites_id = $row['kathigites_id'];

$katastash = $_GET['katastash'] ?? '';
$rolos     = $_GET['rolos'] ?? '';

$query = "
SELECT DISTINCT d.diplwmatikh_id, d.thema, d.katastash,
       xf.Plhres_onoma AS foititis,
       CASE
         WHEN te.kathigiths_id = ? THEN 'Τριμελής Επιτροπή'
         ELSE 'Επιβλέπων'
       END AS rolos
FROM diplwmatikh d
LEFT JOIN mathites m ON d.mathites_id = m.mathites_id
LEFT JOIN xrhstes xf ON m.xrhstes_id = xf.xrhstes_id
LEFT JOIN trimelhs_epitroph te ON d.diplwmatikh_id = te.diplwmatikh_id
WHERE (d.epivlepwn_id = ? OR te.kathigiths_id = ?)
";

$params = [$kathigites_id, $kathigites_id, $kathigites_id];
$types  = "iii";

if ($katastash === 'mh_anatetheimenh') {

    $query .= " AND d.katastash = 'Μη Ανατεθειμένη'";

} elseif ($katastash === 'ypo_anathesi') {

    $query .= " AND d.katastash = 'Προσωρινή Κατοχύρωση'";

} elseif ($katastash !== '') {

    $query .= " AND d.katastash = ?";
    $params[] = $katastash;
    $types   .= "s";
}

if ($rolos === 'Επιβλέπων') {
  $query .= " AND d.epivlepwn_id = ?";
  $params[] = $kathigites_id;
  $types .= "i";
}
if ($rolos === 'Τριμελής Επιτροπή') {
  $query .= " AND te.kathigiths_id = ?";
  $params[] = $kathigites_id;
  $types .= "i";
}

$stmt = $mysql_link->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

echo json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_UNESCAPED_UNICODE);
