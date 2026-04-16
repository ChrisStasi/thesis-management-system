<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος και αν έχει τον σωστό ρόλο
if (!isset($_SESSION['session_username']) || $_SESSION['session_level'] !== 'Foititis') {
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
    <title>Αρχική Σελίδα Φοιτητή</title>
    <link rel="stylesheet" href="foititis.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Καλώς ήρθατε, <span id="userFullName"><?php echo htmlspecialchars($full_name); ?></span>!</h1>
        </header>

        <section class="menu">
            <h2>Επιλογές</h2>
            <ul>
                <li><a href="/foititis/provolithematos/provolithematos.php">Προβολή Θέματος</a></li>
                <li><a href="/foititis/profile/profile.php">Επεξεργασία Προφίλ</a></li>
                <li><a href="/foititis/diaxeirisidiplwmatikis/diaxeirisidiplwmatikis.php">Διαχείριση Διπλωματικής Εργασίας</a></li>
                <li><a href="/foititis/logout/logout.php" class="logout">Αποσύνδεση</a></li>
            </ul>
        </section>
    </div>
    <script src="foititis.js"></script>
</body>
</html>
