<?php session_start(); ?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Επεξεργασία Προφίλ</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Επεξεργασία Προφίλ</h1>
    </header>

    <div id="message"></div>

    <form id="profileForm">
        <div>
            <label>Ονοματεπώνυμο</label>
            <input type="text" id="fullname" disabled>
        </div>

        <div>
            <label>Αριθμός Μητρώου</label>
            <input type="text" id="am" disabled>
        </div>

        <div>
            <label>Email</label>
            <input type="email" id="email" required>
        </div>

        <div>
            <label>Κινητό</label>
            <input type="text" id="thlefwno" required>
        </div>

        <div>
            <label>Σταθερό</label>
            <input type="text" id="stathero">
        </div>

        <div>
            <label>Διεύθυνση</label>
            <input type="text" id="diefthinsi">
        </div>

        <button type="submit">Αποθήκευση</button>
    </form>
</div>

<script src="profile.js"></script>
</body>
</html>





