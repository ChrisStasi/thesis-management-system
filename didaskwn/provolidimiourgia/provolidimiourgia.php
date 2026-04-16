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
<title>Προβολή & Δημιουργία Θεμάτων</title>
<link rel="stylesheet" href="style2.css">
</head>
<body>

<div class="container">
    <h1>Προβολή και Δημιουργία Θεμάτων</h1>

    <!-- Φόρμα Δημιουργίας -->
    <form id="createForm" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Τίτλος" required>
        <textarea name="description" placeholder="Περιγραφή" required></textarea>
        <input type="file" name="file" accept="application/pdf">
        <button type="submit">Καταχώρηση</button>
    </form>

    <hr>

    <!-- Λίστα Θεμάτων -->
    <div id="topicsList"></div>
</div>

<script src="provolidimiourgia.js"></script>
</body>
</html>

