<?php
session_start();
if (!isset($_SESSION['session_id'])) {
    die("Μη εξουσιοδοτημένη πρόσβαση");
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8">
<title>Λίστα Διπλωματικών</title>
<link rel="stylesheet" href="style4.css">
<link rel="stylesheet" href="/didaskwn/menu.css">
</head>
<body>

<div class="menu-icon" id="menu-icon">&#9776;</div>
<div class="menu-options" id="menu-options">
  <ul>
    <li><a href="/didaskwn/provoli_dimiourgia.php">Προβολή & Δημιουργία</a></li>
    <li><a href="/didaskwn/anathesi.php">Ανάθεση Θεμάτων</a></li>
    <li><a href="/didaskwn/lista/lista.php">Λίστα Διπλωματικών</a></li>
    <li><a href="/didaskwn/proskliseis.php">Προσκλήσεις</a></li>
    <li><a href="/didaskwn/statistika/statistika.php">Στατιστικά</a></li>
    <li><a href="/logout.php">Αποσύνδεση</a></li>
  </ul>
</div>
<script src="/didaskwn/menu.js"></script>

<div class="container">
  <h1>Λίστα Διπλωματικών</h1>

  <form id="filters" class="filters">
    <label>Κατάσταση:</label>
<select name="katastash" id="katastash">
    <option value="">Όλες</option>

    <option value="mh_anatetheimenh">
        Μη Ανατεθειμένη
    </option>

    <option value="ypo_anathesi">
        Υπό Ανάθεση (Προσωρινή Κατοχύρωση)
    </option>

    <option value="Ενεργή">
        Ενεργή
    </option>

    <option value="Υπό εξέταση">
        Υπό Εξέταση
    </option>

    <option value="Ολοκληρωμένη">
        Ολοκληρωμένη
    </option>
</select>


    <label>Ρόλος:</label>
    <select name="rolos">
      <option value="">Όλοι</option>
      <option value="Επιβλέπων">Επιβλέπων</option>
      <option value="Τριμελής Επιτροπή">Τριμελής Επιτροπή</option>
    </select>

    <button type="submit">Φιλτράρισμα</button>
  </form>

  <div class="export-actions">
    <button class="export-btn csv" onclick="exportDiplwmatikes('csv')">
    Εξαγωγή CSV
  </button>

  <button class="export-btn json" onclick="exportDiplwmatikes('json')">
    Εξαγωγή JSON
  </button>
  </div>

  <section id="diplwmatikes-list"></section>
</div>

<script src="lista.js"></script>
</body>
</html>
