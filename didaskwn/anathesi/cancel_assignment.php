<?php
session_start();
require_once('../../login/dbconnection.php');
header('Content-Type: application/json; charset=utf-8');

$topic_id = (int)$_POST['topic_id'];

$mysql_link->begin_transaction();

try {
    $stmt = $mysql_link->prepare("DELETE FROM prosklhsh WHERE diplwmatikh_id=?");
    $stmt->bind_param("i", $topic_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysql_link->prepare("DELETE FROM trimelhs_epitroph WHERE diplwmatikh_id=?");
    $stmt->bind_param("i", $topic_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysql_link->prepare("
        UPDATE diplwmatikh
        SET mathites_id=NULL, katastash='Μη Ανατεθειμένη'
        WHERE diplwmatikh_id=?
    ");
    $stmt->bind_param("i", $topic_id);
    $stmt->execute();
    $stmt->close();

    $mysql_link->commit();
    echo json_encode(['status'=>'success']);

} catch (Exception $e) {
    $mysql_link->rollback();
    echo json_encode(['status'=>'error']);
}
$stmt = $mysql_link->prepare("
    SELECT katastash
    FROM diplwmatikh
    WHERE diplwmatikh_id = ?
");
$stmt->bind_param("i", $topic_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($row && $row['katastash'] === 'Ενεργή') {
    echo json_encode([
        'status'=>'error',
        'message'=>'Δεν επιτρέπεται ακύρωση σε ενεργή διπλωματική'
    ]);
    exit;
}
