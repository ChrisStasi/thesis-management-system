function loginSubmit() {
    // Καθαρίζει το μήνυμα αποτυχίας
    document.querySelector("#failedlogin").textContent = "";

    // Παίρνει τις τιμές από τα πεδία της φόρμας
    var username = document.querySelector("#username").value;
    var pwd = document.querySelector("#password").value;

    // Αποστολή των δεδομένων μέσω AJAX (fetch)
    fetch("login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "username=" + username + "&password=" + pwd // Στέλνουμε τα δεδομένα
    })
    .then(response => response.text())
    .then(text => {
        if (text === "Success") {
            // Αν η απάντηση είναι "Success", ανακατευθύνουμε τον χρήστη
            window.location.href = "home.php";  // Εδώ μπορείς να το τροποποιήσεις στην κατάλληλη σελίδα
        } else {
            // Αν η απάντηση είναι κάτι άλλο (δηλαδή λάθος login), εμφανίζουμε το μήνυμα αποτυχίας
            document.querySelector("#failedlogin").textContent = "Wrong username or password";
        }
    });
}

// Εισάγουμε την λειτουργία όταν ο χρήστης πατήσει το κουμπί
document.querySelector("#btn-login").addEventListener("click", loginSubmit);
