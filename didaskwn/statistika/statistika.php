<?php session_start();
if (!isset($_SESSION['session_id'])) {
    die("Μη εξουσιοδοτημένη πρόσβαση");
}
 ?>
<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8">
<title>Στατιστικά Διδάσκοντα</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="style7.css">
</head>

<body>
<div class="container">
    <h1>Στατιστικά Διδάσκοντα</h1>

    <div class="charts-wrapper">
        <div class="chart-box">
            <h3>Μέσος Χρόνος Περάτωσης (μήνες)</h3>
            <canvas id="chartXronos"></canvas>
        </div>

        <div class="chart-box">
            <h3>Μέσος Βαθμός</h3>
            <canvas id="chartVathmos"></canvas>
        </div>

        <div class="chart-box">
            <h3>Συνολικό Πλήθος Διπλωματικών</h3>
            <canvas id="chartSynolo"></canvas>
        </div>
    </div>
</div>

<script src="statistika.js"></script>
</body>
</html>
