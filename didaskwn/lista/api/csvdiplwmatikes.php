<?php
session_start();
require_once('../../../login/dbconnection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ===============================
   Έλεγχος session
   =============================== */
if (!isset($_SESSION['session_id'])) {
    http_response_code(401);
    exit;
}

$xrhstes_id = $_SESSION['session_id'];

/* ===============================
   Εύρεση kathigites_id
   =============================== */
$stmt = $mysql_link->prepare(
    "SELECT kathigites_id FROM kathigites WHERE xrhstes_id = ?"
);
$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(403);
    exit;
}

$kathigites_id = $row['kathigites_id'];

/* ===============================
   Παράμετροι φίλτρων
   =============================== */
$katastash = $_GET['katastash'] ?? '';
$rolos     = $_GET['rolos'] ?? '';
$format    = $_GET['format'] ?? 'json';

/* ===============================
   Query
   =============================== */
$query = "
SELECT DISTINCT
    d.diplwmatikh_id,
    d.thema,
    d.katastash,
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

/* ---- Φίλτρο κατάστασης ---- */
if ($katastash === 'mh_anatetheimenh') {

    $query .= " AND d.katastash = 'Μη Ανατεθειμένη'";

} elseif ($katastash === 'ypo_anathesi') {

    $query .= " AND d.katastash = 'Προσωρινή Κατοχύρωση'";

} elseif ($katastash !== '') {

    $query .= " AND d.katastash = ?";
    $params[] = $katastash;
    $types   .= "s";
}


/* ---- Φίλτρο ρόλου ---- */
if ($rolos === 'Επιβλέπων') {
    $query .= " AND d.epivlepwn_id = ?";
    $params[] = $kathigites_id;
    $types   .= "i";
}

if ($rolos === 'Τριμελής Επιτροπή') {
    $query .= " AND te.kathigiths_id = ?";
    $params[] = $kathigites_id;
    $types   .= "i";
}

/* ===============================
   Εκτέλεση
   =============================== */
$stmt = $mysql_link->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res  = $stmt->get_result();
$data = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

/* ===============================
   CSV EXPORT
   =============================== */
if ($format === 'csv') {

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=diplwmatikes.csv');

    $out = fopen('php://output', 'w');

    // BOM για Excel (ΠΟΛΥ ΣΗΜΑΝΤΙΚΟ)
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

    if (!empty($data)) {
        fputcsv($out, array_keys($data[0]), ';');
foreach ($data as $row) {
    fputcsv($out, $row, ';');
}

    }

    fclose($out);
    exit;
}

/* ===============================
   JSON EXPORT (default)
   =============================== */
header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename=diplwmatikes.json');

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;
