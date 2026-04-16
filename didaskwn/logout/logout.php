<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8">
<title>Αποσύνδεση</title>
</head>
<body>

<script>
const confirmLogout = confirm('Είστε σίγουρος ότι θέλετε να αποσυνδεθείτε;');

if (confirmLogout) {
    fetch('/login/logout.php', {
        method: 'POST',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/login/login.php';
        } else {
            alert('Η αποσύνδεση απέτυχε.');
        }
    })
    .catch(() => {
        alert('Σφάλμα επικοινωνίας με τον διακομιστή.');
    });
} else {
    window.history.back();
}
</script>

</body>
</html>

