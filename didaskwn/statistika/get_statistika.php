<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$xrhstes_id = intval($_SESSION['session_id']);

/* kathigiths_id */
$stmt = $mysql_link->prepare(
    "SELECT kathigites_id FROM kathigites WHERE xrhstes_id = ?"
);
$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(['status'=>'error','message'=>'Καθηγητής δεν βρέθηκε']);
    exit;
}

$kathigiths_id = $row['kathigites_id'];

/* ===============================
   ΜΕΣΟΣ ΧΡΟΝΟΣ
   =============================== */
$stmt = $mysql_link->prepare("
    SELECT AVG(TIMESTAMPDIFF(MONTH, anatethike, oloklirwthike)) m
    FROM diplwmatikh
    WHERE katastash='Ολοκληρωμένη'
      AND epivlepwn_id=?
");
$stmt->bind_param("i",$kathigiths_id);
$stmt->execute();
$epivlepwn_xronos = $stmt->get_result()->fetch_assoc()['m'] ?? 0;
$stmt->close();

$stmt = $mysql_link->prepare("
    SELECT AVG(TIMESTAMPDIFF(MONTH,d.anatethike,d.oloklirwthike)) m
    FROM diplwmatikh d
    JOIN trimelhs_epitroph t ON d.diplwmatikh_id=t.diplwmatikh_id
    WHERE d.katastash='Ολοκληρωμένη'
      AND t.kathigiths_id=?
");
$stmt->bind_param("i",$kathigiths_id);
$stmt->execute();
$trimelhs_xronos = $stmt->get_result()->fetch_assoc()['m'] ?? 0;
$stmt->close();

/* ===============================
   ΜΕΣΟΣ ΒΑΘΜΟΣ
   =============================== */
$stmt = $mysql_link->prepare("
    SELECT AVG(v.telikos_vathmos) m
    FROM vathmos v
    JOIN diplwmatikh d ON v.diplwmatikh_id=d.diplwmatikh_id
    WHERE d.katastash='Ολοκληρωμένη'
      AND d.epivlepwn_id=?
");
$stmt->bind_param("i",$kathigiths_id);
$stmt->execute();
$epivlepwn_vathmos = $stmt->get_result()->fetch_assoc()['m'] ?? 0;
$stmt->close();

$stmt = $mysql_link->prepare("
    SELECT AVG(v.telikos_vathmos) m
    FROM vathmos v
    JOIN diplwmatikh d ON v.diplwmatikh_id=d.diplwmatikh_id
    JOIN trimelhs_epitroph t ON d.diplwmatikh_id=t.diplwmatikh_id
    WHERE d.katastash='Ολοκληρωμένη'
      AND t.kathigiths_id=?
");
$stmt->bind_param("i",$kathigiths_id);
$stmt->execute();
$trimelhs_vathmos = $stmt->get_result()->fetch_assoc()['m'] ?? 0;
$stmt->close();

/* ===============================
   ΣΥΝΟΛΟ
   =============================== */
$stmt = $mysql_link->prepare(
    "SELECT COUNT(*) c FROM diplwmatikh WHERE epivlepwn_id=?"
);
$stmt->bind_param("i",$kathigiths_id);
$stmt->execute();
$epivlepwn_synolo = $stmt->get_result()->fetch_assoc()['c'];
$stmt->close();

$stmt = $mysql_link->prepare(
    "SELECT COUNT(DISTINCT diplwmatikh_id) c
     FROM trimelhs_epitroph WHERE kathigiths_id=?"
);
$stmt->bind_param("i",$kathigiths_id);
$stmt->execute();
$trimelhs_synolo = $stmt->get_result()->fetch_assoc()['c'];
$stmt->close();

/* ===============================
   RESPONSE
   =============================== */
echo json_encode([
    'status'=>'success',
    'xronos'=>[
        'Επιβλέπων'=>round($epivlepwn_xronos,2),
        'Μέλος Τριμελούς'=>round($trimelhs_xronos,2)
    ],
    'vathmos'=>[
        'Επιβλέπων'=>round($epivlepwn_vathmos,2),
        'Μέλος Τριμελούς'=>round($trimelhs_vathmos,2)
    ],
    'synolo'=>[
        'Επιβλέπων'=>$epivlepwn_synolo,
        'Μέλος Τριμελούς'=>$trimelhs_synolo
    ]
]);
