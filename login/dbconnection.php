<?php
// Ρύθμιση σύνδεσης με τη βάση δεδομένων
$mysql_link = new mysqli('localhost', 'root', '', 'diplwmatikh_erg2');

// Έλεγχος για τυχόν σφάλμα σύνδεσης
if ($mysql_link->connect_error) {
    die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
}
?>
