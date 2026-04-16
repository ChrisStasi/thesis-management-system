<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['session_id'])) {
    die("Μη εξουσιοδοτημένη πρόσβαση");
}

if (!isset($_GET['diplwmatikh_id'])) {
    die("Δεν ορίστηκε διπλωματική");
}

$diplwmatikh_id = (int)$_GET['diplwmatikh_id'];
?>
<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8">
<title>Λεπτομέρειες Διπλωματικής</title>
<link rel="stylesheet" href="style5.css">
</head>

<body>
<div class="container" id="app">

  <h1>Λεπτομέρειες Διπλωματικής</h1>

  <div id="loading">Φόρτωση...</div>
  <div id="error" style="display:none;"></div>

  <section id="basic-info"></section>
  <hr>

  <section id="committee" style="display:none;"></section>
  <hr id="hr-committee" style="display:none;">

  <section id="invites" style="display:none;"></section>

  <section id="cancel-area" style="display:none;"></section>

  <section id="notes" style="display:none;"></section>

  <div id="notes-list">
  <!-- εδώ θα εμφανίζονται οι αποθηκευμένες σημειώσεις -->
</div>

  <section id="grades" style="display:none;"></section>

</div>

<script>
  // Δίνουμε το id στη JS με ασφαλή τρόπο (ΜΟΝΟ αριθμός)
  window.DIPLO_ID = <?= (int)$diplwmatikh_id ?>;
</script>
<script src="details.js"></script>
</body>
</html>
