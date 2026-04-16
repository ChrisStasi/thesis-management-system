<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['status'=>'error']);
    exit;
}

$action = $_POST['action'] ?? '';
$diplwmatikh_id = intval($_POST['diplwmatikh_id'] ?? 0);

if (!in_array($action,['accept','reject'])) {
    echo json_encode(['status'=>'error','message'=>'Invalid action']);
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

$mysql_link->begin_transaction();

try {
    $apanthsh = ($action === 'accept') ? 'Δεκτή' : 'Απορρίπτεται';

    $stmt = $mysql_link->prepare(
        "UPDATE prosklhsh
         SET apanthsh=?, hmeromhnia_apanthshs=NOW()
         WHERE kathigitis_id=? AND diplwmatikh_id=?"
    );
    $stmt->bind_param("sii", $apanthsh, $kathigitis_id, $diplwmatikh_id);
    $stmt->execute();
    $stmt->close();

    if ($apanthsh === 'Δεκτή') {
        $stmt = $mysql_link->prepare(
            "SELECT COUNT(*) c FROM prosklhsh
             WHERE diplwmatikh_id=? AND apanthsh='Δεκτή'"
        );
        $stmt->bind_param("i", $diplwmatikh_id);
        $stmt->execute();
        $count = $stmt->get_result()->fetch_assoc()['c'];
        $stmt->close();

        if ($count >= 2) {
            $mysql_link->query(
                "UPDATE diplwmatikh
                 SET katastash='Ενεργή'
                 WHERE diplwmatikh_id=$diplwmatikh_id"
            );

            $mysql_link->query(
                "DELETE FROM prosklhsh
                 WHERE diplwmatikh_id=$diplwmatikh_id
                 AND apanthsh IS NULL"
            );
        }
    }

    $mysql_link->commit();
    echo json_encode(['status'=>'success','message'=>'Η απάντηση καταχωρήθηκε']);

} catch (Exception $e) {
    $mysql_link->rollback();
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
