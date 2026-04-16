<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = intval($_SESSION['session_id']);

$query = "
SELECT 
    d.diplwmatikh_id,
    d.thema,
    d.perigrafh,
    d.link,
    d.katastash,
    xs.Plhres_onoma AS epivlepwn,

    GROUP_CONCAT(
        CONCAT(xm.Plhres_onoma, ' (', te.rolos, ')')
        SEPARATOR ', '
    ) AS trimelis,

    TIMESTAMPDIFF(DAY, d.anatethike, NOW()) AS meres_anathesis

FROM diplwmatikh d

/* ---------- Επιβλέπων ---------- */
LEFT JOIN kathigites ks ON d.epivlepwn_id = ks.kathigites_id
LEFT JOIN xrhstes xs ON ks.xrhstes_id = xs.xrhstes_id

/* ---------- Τριμελής ---------- */
LEFT JOIN trimelhs_epitroph te 
    ON te.diplwmatikh_id = d.diplwmatikh_id

LEFT JOIN kathigites km 
    ON te.kathigiths_id = km.kathigites_id

LEFT JOIN xrhstes xm 
    ON km.xrhstes_id = xm.xrhstes_id

WHERE d.mathites_id = (
    SELECT mathites_id 
    FROM mathites 
    WHERE xrhstes_id = ?
)

GROUP BY d.diplwmatikh_id
";

$stmt = $mysql_link->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode([
    'status' => 'success',
    'data'   => $data
]);

