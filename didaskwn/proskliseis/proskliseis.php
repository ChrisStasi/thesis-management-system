<?php session_start();
if (!isset($_SESSION['session_id'])) {
    die("Μη εξουσιοδοτημένη πρόσβαση");
} 
?>
<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8">
<title>Προσκλήσεις Συμμετοχής</title>
<link rel="stylesheet" href="style6.css">
</head>
<body>

<div class="container">
    <h1>Προσκλήσεις Συμμετοχής</h1>
    <ul id="invitationList"></ul>
</div>

<script src="proskliseis.js"></script>
</body>
</html>
