<?php
session_start();
require_once('../../login/dbconnection.php');

header('Content-Type: application/json; charset=utf-8');

// 🔴 ΑΣΦΑΛΕΙΑ: αν δεν υπάρχει session
if (!isset($_SESSION['session_id'])) {
    echo json_encode([
        'success' => false,
        'thesis' => null,
        'error' => 'no-session'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$user_id = intval($_SESSION['session_id']);

/* ===============================
   ΑΝΑΚΤΗΣΗ ΔΙΠΛΩΜΑΤΙΚΗΣ
   =============================== */
$stmt = $mysql_link->prepare("
   SELECT d.diplwmatikh_id,
           d.thema,
           d.katastash,
           d.link,
           d.proxeiro_arxeio,
           d.hmera_wra_parousiashs,
           d.typos_parousiashs,
           d.topothesia_parousiashs,
           d.nimertis_url,
           d.nimertis_submitted_at,
           xs.Plhres_onoma AS foititis
    FROM diplwmatikh d
    JOIN mathites m ON d.mathites_id = m.mathites_id
    JOIN xrhstes xs ON m.xrhstes_id = xs.xrhstes_id
    WHERE m.xrhstes_id = ?
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$thesis = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* ===============================
   ΑΝ ΔΕΝ ΥΠΑΡΧΕΙ ΔΙΠΛΩΜΑΤΙΚΗ
   =============================== */
if (!$thesis) {
    echo json_encode([
        'success' => true,
        'thesis' => null
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/* ===============================
   ΑΝΑΚΤΗΣΗ ΠΡΟΣΚΛΗΣΕΩΝ
   =============================== */
$stmt = $mysql_link->prepare("
    SELECT x.Plhres_onoma,
           p.apanthsh
    FROM prosklhsh p
    JOIN kathigites k ON p.kathigitis_id = k.kathigites_id
    JOIN xrhstes x ON k.xrhstes_id = x.xrhstes_id
    WHERE p.diplwmatikh_id = ?
");
$stmt->bind_param("i", $thesis['diplwmatikh_id']);
$stmt->execute();
$invitations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();


/* ===============================
   ΠΡΑΚΤΙΚΟ ΕΞΕΤΑΣΗΣ
   =============================== */
$praktiko = [
    'ready' => false
];

/* Αν είναι Υπό Εξέταση → έλεγξε βαθμούς */
if ($thesis['katastash'] === 'Υπό εξέταση') {

    // Πόσα μέλη τριμελούς
    $stmt = $mysql_link->prepare("
        SELECT COUNT(*) AS total
        FROM trimelhs_epitroph
        WHERE diplwmatikh_id = ?
    ");
    $stmt->bind_param("i", $thesis['diplwmatikh_id']);
    $stmt->execute();
    $total = (int)$stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();

    // Βαθμοί
    $stmt = $mysql_link->prepare("
        SELECT x.Plhres_onoma,
               v.telikos_vathmos
        FROM vathmos v
        JOIN kathigites k ON v.kathigitis_id = k.kathigites_id
        JOIN xrhstes x ON k.xrhstes_id = x.xrhstes_id
        WHERE v.diplwmatikh_id = ?
    ");
    $stmt->bind_param("i", $thesis['diplwmatikh_id']);
    $stmt->execute();
    $grades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    /* 👉 ΠΡΑΚΤΙΚΟ ΜΟΝΟ ΑΝ ΕΧΟΥΝ ΒΑΘΜΟΛΟΓΗΣΕΙ ΟΛΟΙ */
    if ($total === 3 && count($grades) === 3) {

        $sum = 0;
        foreach ($grades as $g) {
            $sum += (float)$g['telikos_vathmos'];
        }

        $praktiko = [
            'ready'   => true,
            'grades'  => $grades,
            'average' => round($sum / 3, 2)
        ];

    }
}

$nimertis = [
    'allowed' => false,
    'submitted' => false,
    'url' => null
];

if ($praktiko['ready'] === true) {
    $nimertis['allowed'] = true;

    if (!empty($thesis['nimertis_url'])) {
        $nimertis['submitted'] = true;
        $nimertis['url'] = $thesis['nimertis_url'];
    }
}



/* ===============================
   ΤΕΛΙΚΟ JSON
   =============================== */
echo json_encode([
    'success'     => true,
    'thesis'      => $thesis,
    'invitations' => $invitations,
    'praktiko'    => $praktiko,
    'nimertis'    => $nimertis
], JSON_UNESCAPED_UNICODE);


exit;


