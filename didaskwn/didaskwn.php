<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος και αν έχει τον σωστό ρόλο
if (!isset($_SESSION['session_username']) || $_SESSION['session_level'] !== 'Didaskwn') {
    header("Location: /login/login.php");
    exit();
}

// Φόρτωμα στοιχείων από το session
$username = $_SESSION['session_username'];
$full_name = $_SESSION['session_fullname'];
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Αρχική Σελίδα Διδάσκοντα</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Καλώς ήρθατε, <span id="userFullName"><?php echo htmlspecialchars($full_name); ?></span>!</h1>
        </header>

        <section class="menu">
            <h2>Επιλογές</h2>
            <ul>
                <li><a href="/didaskwn/provolidimiourgia/provolidimiourgia.php">Προβολή και Δημιουργία Θεμάτων</a></li>
                <li><a href="/didaskwn/anathesi/anathesi.php">Ανάθεση Θεμάτων σε Φοιτητές</a></li>
                <li><a href="/didaskwn/lista/lista.php">Λίστα Διπλωματικών</a></li>
                <li><a href="/didaskwn/proskliseis/proskliseis.php">Προσκλήσεις Συμμετοχής σε Τριμελή</a></li>
                <li><a href="/didaskwn/statistika/statistika.php">Προβολή Στατιστικών</a></li>
                <li><a href="/didaskwn/logout/logout.php" class="logout">Αποσύνδεση</a></li>
            </ul>
        </section>
    </div>
    <script src="didaskwn.js"></script>
</body>
</html>
