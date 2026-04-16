document.addEventListener("DOMContentLoaded", loadInvitations);

function loadInvitations() {
    fetch("get_proskliseis.php")
        .then(r => r.json())
        .then(d => {
            const ul = document.getElementById("invitationList");
            ul.innerHTML = "";

            if (d.data.length === 0) {
                ul.innerHTML = "<p>Δεν υπάρχουν εκκρεμείς προσκλήσεις.</p>";
                return;
            }

            d.data.forEach(p => {
                const li = document.createElement("li");
                li.className = "invitation-card";
                li.innerHTML = `
                    <h3>${p.thema}</h3>
                    <p><b>Φοιτητής:</b> ${p.student_name}</p>
                    <p><b>Επιβλέπων:</b> ${p.supervisor_name}</p>
                    <p>${p.perigrafh ?? ""}</p>

                    <button class="accept"
        onclick="respond(${p.diplwmatikh_id},'accept')">
    Αποδοχή
</button>

<button class="reject"
        onclick="respond(${p.diplwmatikh_id},'reject')">
    Απόρριψη
</button>

                `;
                ul.appendChild(li);
            });
        });
}

function respond(id, action) {
    fetch("apantisi_prosklisis.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            action: action,
            diplwmatikh_id: id
        })
    })
    .then(r => r.json())
    .then(d => {
        alert(d.message);
        if (d.status === "success") loadInvitations();
    });
}
