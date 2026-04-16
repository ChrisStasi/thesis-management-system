document.addEventListener("DOMContentLoaded", loadDiplwmatikh);

function loadDiplwmatikh() {
    fetch("thema_diplwmatikis.php")
        .then(r => r.json())
        .then(d => {
            const container = document.getElementById("content");
            container.innerHTML = "";

            if (d.status !== "success" || d.data.length === 0) {
                container.innerHTML =
                    "<p>Δεν έχει ανατεθεί ακόμα κάποια διπλωματική εργασία.</p>";
                return;
            }

            d.data.forEach(item => {
                const div = document.createElement("div");
                div.className = "diplwmatikh-details";

                div.innerHTML = `
                    <h2>${item.thema}</h2>

                    <p><strong>Περιγραφή:</strong><br>
                    ${item.perigrafh ? item.perigrafh.replace(/\n/g,"<br>") : "-"}</p>

                    <p><strong>Σύνδεσμος Αρχείου:</strong>
                        ${
                            item.link
                                ? `<a href="/uploads/${item.link}" target="_blank">Προβολή Αρχείου</a>`
                                : "Δεν υπάρχει αρχείο."
                        }
                    </p>

                    <p><strong>Κατάσταση:</strong> ${item.katastash}</p>
                    <p><strong>Επιβλέπων:</strong> ${item.epivlepwn ?? "-"}</p>

                    <p><strong>Μέλη Τριμελούς Επιτροπής:</strong></p>
                    <ul>
    ${
        item.trimelis
            ? item.trimelis
                .split(", ")
                .map(melos => `<li>${melos}</li>`)
                .join("")
            : "<li>Δεν έχουν οριστεί μέλη.</li>"
    }
</ul>


                    ${
                        item.meres_anathesis
                            ? `<p><strong>Χρόνος από Ανάθεση:</strong>
                               ${item.meres_anathesis} ημέρες</p>`
                            : ""
                    }
                `;

                container.appendChild(div);
            });
        })
        .catch(() => {
            document.getElementById("content").innerHTML =
                "<p>Σφάλμα φόρτωσης δεδομένων.</p>";
        });
}
