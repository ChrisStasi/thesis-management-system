<?php
session_start();
require_once('../../login/dbconnection.php');

if (!isset($_SESSION['session_id']) || $_SESSION['session_level'] !== 'Foititis') {
    header("Location: /login/login.php");
    exit;
}

$user_id = intval($_SESSION['session_id']);

/* ===== Φέρνουμε τη διπλωματική ===== */
$stmt = $mysql_link->prepare("
    SELECT d.diplwmatikh_id, d.thema, d.katastash,
           xs.Plhres_onoma AS epivlepwn
    FROM diplwmatikh d
    JOIN mathites m ON d.mathites_id = m.mathites_id
    LEFT JOIN kathigites k ON d.epivlepwn_id = k.kathigites_id
    LEFT JOIN xrhstes xs ON k.xrhstes_id = xs.xrhstes_id
    WHERE m.xrhstes_id = ?
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$diplwmatikh = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* ===== Προσκλήσεις ===== */
$invitations = [];
if ($diplwmatikh) {
    $stmt = $mysql_link->prepare("
        SELECT x.Plhres_onoma, p.apanthsh
        FROM prosklhsh p
        JOIN kathigites k ON p.kathigitis_id = k.kathigites_id
        JOIN xrhstes x ON k.xrhstes_id = x.xrhstes_id
        WHERE p.diplwmatikh_id = ?
        ORDER BY x.Plhres_onoma
    ");
    $stmt->bind_param("i", $diplwmatikh['diplwmatikh_id']);
    $stmt->execute();
    $invitations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Διαχείριση Διπλωματικής</title>
    <link rel="stylesheet" href="diaxeirisidiplwmatikis.css">
</head>

<body>
<div class="container">

<?php if (!$diplwmatikh): ?>

    <div class="card">
        <h1 class="page-title">Διαχείριση Διπλωματικής</h1>
        <p class="muted">Δεν έχει ανατεθεί ακόμα διπλωματική εργασία.</p>
    </div>

<?php else: ?>

    <!-- Βασικά στοιχεία -->
    <div class="card">
        <h1 class="page-title"><?= htmlspecialchars($diplwmatikh['thema']) ?></h1>

        <div class="meta">
            <div class="meta-row">
                <span class="meta-label">Κατάσταση</span>
                <span class="meta-value badge"><?= htmlspecialchars($diplwmatikh['katastash']) ?></span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Επιβλέπων</span>
                <span class="meta-value"><?= htmlspecialchars($diplwmatikh['epivlepwn'] ?? '-') ?></span>
            </div>
        </div>
    </div>

    <!-- Προσκλήσεις -->
    <div class="card">
        <h2 class="section-title">Προσκλήσεις</h2>

        <ul class="inv-list">
            <?php if ($invitations): ?>
                <?php foreach ($invitations as $inv): ?>
                    <li class="inv-item">
                        <span><?= htmlspecialchars($inv['Plhres_onoma']) ?></span>
                        <span><?= htmlspecialchars($inv['apanthsh'] ?? 'Εκκρεμεί') ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="inv-empty">Δεν υπάρχουν προσκλήσεις.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Πρόσκληση νέου μέλους -->
   <!-- CARD ΠΡΟΣΚΛΗΣΗΣ -->
<div id="invite-card" class="invite-card">
  <h3 class="invite-title">Πρόσκληση Νέου Μέλους</h3>

  <div class="invite-controls">
    <select id="lecturer-select">
      <option value="">— Επιλέξτε διδάσκοντα —</option>
    </select>

    <button id="inviteBtn" class="invite-btn">
      Αποστολή Πρόσκλησης
    </button>
  </div>

  <p id="invitation-message" class="invite-message"></p>
</div>

<!-- 🔒 ΜΗΝΥΜΑ ΚΛΕΙΔΩΜΑΤΟΣ (ΕΞΩ ΑΠΟ ΤΟ CARD) -->
<div id="invite-locked-slot"></div>


    <!-- 🔵 ΥΠΟ ΕΞΕΤΑΣΗ – PLACEHOLDER -->
    <div id="under-review-sections"></div>

<?php endif; ?>

</div>

<?php if ($diplwmatikh): ?>
<script>
const DIPLOMATIKI_ID = <?= (int)$diplwmatikh['diplwmatikh_id'] ?>;
const DIPLOMATIKI_KATASTASH = <?= json_encode($diplwmatikh['katastash']) ?>;
</script>
<script src="diaxeirisidiplwmatikis.js"></script>
<?php endif; ?>

</body>
</html>


