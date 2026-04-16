document.addEventListener("DOMContentLoaded", loadStats);

function loadStats() {
    fetch("get_statistika.php")
        .then(r => r.json())
        .then(d => {
            if (d.status !== "success") {
                alert("Σφάλμα φόρτωσης στατιστικών");
                return;
            }

            drawBar("chartXronos", d.xronos, "Μήνες");
            drawLine("chartVathmos", d.vathmos, "Βαθμός");
            drawPie("chartSynolo", d.synolo);
        });
}

function drawBar(id, data, label) {
    new Chart(document.getElementById(id), {
        type: "bar",
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: label,
                data: Object.values(data),
                backgroundColor: ["#60a5fa", "#93c5fd"]
            }]
        }
    });
}

function drawLine(id, data, label) {
    new Chart(document.getElementById(id), {
        type: "line",
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: label,
                data: Object.values(data),
                borderColor: "#2563eb",
                backgroundColor: "#bfdbfe",
                fill: false
            }]
        }
    });
}

function drawPie(id, data) {
    new Chart(document.getElementById(id), {
        type: "pie",
        data: {
            labels: Object.keys(data),
            datasets: [{
                data: Object.values(data),
                backgroundColor: ["#2563eb", "#14b8a6"]
            }]
        }
    });
}
