<?php
session_start();
require_once('../../../login/dbconnection.php');

header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ============================================================
   ΕΛΕΓΧΟΙ
   ============================================================ */
if (!isset($_SESSION['session_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Μη εξουσιοδοτημένη πρόσβαση']);
    exit;
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Δεν ορίστηκε διπλωματική']);
    exit;
}

$diplwmatikh_id = (int)$_GET['id'];
$xrhstes_id     = $_SESSION['session_id'];

/* ============================================================
   ΕΥΡΕΣΗ kathigiths_id
   ============================================================ */
$stmt = $mysql_link->prepare(
    "SELECT kathigites_id FROM kathigites WHERE xrhstes_id = ?"
);
$stmt->bind_param("i", $xrhstes_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(403);
    echo json_encode(['error' => 'Ο χρήστης δεν είναι διδάσκων']);
    exit;
}

$kathigiths_id = (int)$row['kathigites_id'];

/* ============================================================
   ΑΝΑΚΤΗΣΗ ΔΙΠΛΩΜΑΤΙΚΗΣ
   ============================================================ */
$stmt = $mysql_link->prepare("
    SELECT d.*,
           xs.Plhres_onoma AS foititis,
           xk.Plhres_onoma AS epivlepwn
    FROM diplwmatikh d
    LEFT JOIN mathites m ON d.mathites_id = m.mathites_id
    LEFT JOIN xrhstes xs ON m.xrhstes_id = xs.xrhstes_id
    LEFT JOIN kathigites k ON d.epivlepwn_id = k.kathigites_id
    LEFT JOIN xrhstes xk ON k.xrhstes_id = xk.xrhstes_id
    WHERE d.diplwmatikh_id = ?
");
$stmt->bind_param("i", $diplwmatikh_id);
$stmt->execute();
$diplwmatikh = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$diplwmatikh) {
    http_response_code(404);
    echo json_encode(['error' => 'Η διπλωματική δεν βρέθηκε']);
    exit;
}

/* ============================================================
   ΡΟΛΟΙ
   ============================================================ */
$is_epivlepwn = ((int)$diplwmatikh['epivlepwn_id'] === $kathigiths_id);

$stmt = $mysql_link->prepare("
    SELECT 1
    FROM trimelhs_epitroph
    WHERE diplwmatikh_id = ? AND kathigiths_id = ?
");
$stmt->bind_param("ii", $diplwmatikh_id, $kathigiths_id);
$stmt->execute();
$is_trimelhs = $stmt->get_result()->num_rows > 0;
$stmt->close();

/* ============================================================
   ΚΑΤΑΣΤΑΣΗ
   ============================================================ */
$is_under_assignment = in_array(
    $diplwmatikh['katastash'],
    ['Υπό ανάθεση', 'Προσωρινή Κατοχύρωση']
);

/* ============================================================
   ΠΡΟΣΚΛΗΣΕΙΣ
   ============================================================ */
$proskliseis = [];

if ($is_under_assignment) {
    $stmt = $mysql_link->prepare("
        SELECT x.Plhres_onoma,
               p.apanthsh,
               p.hmeromhnia_prosklhshs,
               p.hmeromhnia_apanthshs
        FROM prosklhsh p
        JOIN kathigites k ON p.kathigitis_id = k.kathigites_id
        JOIN xrhstes x ON k.xrhstes_id = x.xrhstes_id
        WHERE p.diplwmatikh_id = ?
    ");
    $stmt->bind_param("i", $diplwmatikh_id);
    $stmt->execute();
    $proskliseis = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

/* ============================================================
   ΒΑΘΜΟΛΟΓΙΕΣ
   ============================================================ */
$stmt = $mysql_link->prepare("
    SELECT 
        v.poiotita,
        v.xronos,
        v.keimeno,
        v.parousiasi,
        v.telikos_vathmos,
        v.hmeromhnia,
        x.Plhres_onoma AS kathigitis
    FROM vathmos v
    JOIN kathigites k ON v.kathigitis_id = k.kathigites_id
    JOIN xrhstes x ON k.xrhstes_id = x.xrhstes_id
    WHERE v.diplwmatikh_id = ?
");
if (!$stmt) {
    die('SQL ERROR (vathmos): ' . $mysql_link->error);
}

$stmt->bind_param("i", $diplwmatikh_id);
$stmt->execute();
$vathmoi = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

/* ============================================================
   ΤΡΙΜΕΛΗΣ (ΑΝ ΕΝΕΡΓΗ)
   ============================================================ */
$trimelhs_melh = [];

if (in_array($diplwmatikh['katastash'], ['Ενεργή', 'Υπό εξέταση'])){
    $stmt = $mysql_link->prepare("
        SELECT x.Plhres_onoma
        FROM trimelhs_epitroph t
        JOIN kathigites k ON t.kathigiths_id = k.kathigites_id
        JOIN xrhstes x ON k.xrhstes_id = x.xrhstes_id
        WHERE t.diplwmatikh_id = ?
    ");
    $stmt->bind_param("i", $diplwmatikh_id);
    $stmt->execute();
    $trimelhs_melh = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

/* ============================================================
   RESPONSE
   ============================================================ */
echo json_encode([
    'diplwmatikh' => [
        'diplwmatikh_id' => (int)$diplwmatikh['diplwmatikh_id'],
        'thema' => $diplwmatikh['thema'],
        'katastash' => $diplwmatikh['katastash'],
        'foititis' => $diplwmatikh['foititis'],
        'epivlepwn' => $diplwmatikh['epivlepwn'],
        'epivlepwn_id' => (int)$diplwmatikh['epivlepwn_id'],
        'hmera_wra_parousiashs' => $diplwmatikh['hmera_wra_parousiashs'],
        'typos_parousiashs' => $diplwmatikh['typos_parousiashs'],
        'topothesia_parousiashs' => $diplwmatikh['topothesia_parousiashs'],
        'proxeiro_arxeio' => $diplwmatikh['proxeiro_arxeio'],
        'energopoihsh_eisodou_vathmou' =>
            (int)$diplwmatikh['energopoihsh_eisodou_vathmou']
    ],
    'is_epivlepwn' => $is_epivlepwn,
    'is_trimelhs' => $is_trimelhs,
    'is_under_assignment' => $is_under_assignment,
    'proskliseis' => $proskliseis,
    'vathmoi' => $vathmoi,
    'trimelhs_melh' => $trimelhs_melh
]);

exit;
