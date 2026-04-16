<?php
session_start();
require_once('dbconnection.php'); // Σύνδεση με τη βάση δεδομένων

// Ελέγχουμε αν τα δεδομένα ήρθαν μέσω POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Λήψη δεδομένων από την φόρμα
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Ερώτημα για έλεγχο του χρήστη στη βάση με prepared statement
    $query = "SELECT * FROM xrhstes WHERE username=BINARY ? AND kwdikos=BINARY ?";
    $stmt = $mysql_link->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Εάν βρεθεί ο χρήστης, αποθήκευση στοιχείων στη session
        $row = $result->fetch_assoc();
        $_SESSION['session_username'] = $row['username'];
        $_SESSION['session_level'] = $row['rolos'];
        $_SESSION['session_fullname'] = $row['Plhres_onoma']; // Αποθηκεύουμε το πλήρες όνομα του χρήστη
        $_SESSION['session_id'] = $row['xrhstes_id']; // Αποθηκεύουμε το ID του χρήστη

        // Επιστροφή κατάλληλης απάντησης για JavaScript
        if ($row['rolos'] === 'Didaskwn') {
            echo "Didaskwn";
        } elseif ($row['rolos'] === 'Foititis') {
            echo "Foititis";
        } elseif ($row['rolos'] === 'Grammateia') {
            echo "Grammateia";
        } else {
            echo "Invalid";
        }
    } else {
        // Επιστροφή μήνυμα αποτυχίας
        echo "Invalid";
    }

    $stmt->close();
    $mysql_link->close();
    exit;  // Αποφεύγουμε την ανανέωση της σελίδας
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <form id="loginForm" method="POST">
            <h2>Σύνδεση</h2>
            <div class="input-group">
                <label for="username">Όνομα Χρήστη</label>
                <input type="text" id="username" name="username" placeholder="Εισάγετε το όνομα χρήστη" required>
            </div>
            <div class="input-group">
                <label for="password">Κωδικός Πρόσβασης</label>
                <input type="password" id="password" name="password" placeholder="Εισάγετε τον κωδικό πρόσβασης" required>
            </div>
            <button type="submit" id="btn-login">Σύνδεση</button>
            <p id="failedlogin" class="error"></p>
        </form>
    </div>

    <script>
    // Λειτουργία που εκτελείται όταν ο χρήστης υποβάλει τη φόρμα
    document.querySelector("#loginForm").onsubmit = function(event) {
        event.preventDefault(); // Αποφυγή ανανέωσης της σελίδας

        // Καθαρίζει το μήνυμα αποτυχίας
        document.querySelector("#failedlogin").textContent = "";

        // Παίρνει τις τιμές από τα πεδία της φόρμας
        const username = document.querySelector("#username").value;
        const password = document.querySelector("#password").value;

        // Αποστολή των δεδομένων μέσω AJAX (fetch)
        fetch("login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        })
        .then(response => response.text())
        .then(rolos => {
            if (rolos === "Didaskwn") {
                window.location.href = "/didaskwn/didaskwn.php";
            } else if (rolos === "Foititis") {
                window.location.href = "/foititis/foititis.php";
            } else if (rolos === "Grammateia") {
                window.location.href = "/grammateia/grammateia.php";
            } else {
                document.querySelector("#failedlogin").textContent = "Λανθασμένο όνομα χρήστη ή κωδικός.";
            }
        })
        .catch(error => {
            document.querySelector("#failedlogin").textContent = "Παρουσιάστηκε σφάλμα. Προσπαθήστε ξανά.";
            console.error("Σφάλμα:", error);
        });
    };
    </script>
</body>
</html>
