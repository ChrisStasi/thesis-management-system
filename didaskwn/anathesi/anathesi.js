/* ======================================================
   GLOBAL STATE
   ====================================================== */
let selectedStudent = null;     // { mathites_id, am, Plhres_onoma }
let studentLocked = false;     // true αν ο φοιτητής έχει ήδη ΕΝΕΡΓΗ διπλωματική
let topicsCache = [];          // όλα τα θέματα από backend

/* ======================================================
   Φόρτωση θεμάτων
   ====================================================== */
document.addEventListener("DOMContentLoaded", () => {
    loadTopics();
});

function loadTopics() {
    fetch("get_topics.php")
        .then(r => r.json())
        .then(data => {
            if (data.status !== "success") {
                alert(data.message || "Σφάλμα φόρτωσης θεμάτων");
                return;
            }
            topicsCache = data.topics;
            renderTopics();
        })
        .catch(() => alert("Σφάλμα δικτύου"));
}

/* ======================================================
   Render θεμάτων
   ====================================================== */
function renderTopics() {
    const container = document.getElementById("topicsList");
    container.innerHTML = "";

    if (!topicsCache.length) {
        container.innerHTML = "<p>Δεν υπάρχουν θέματα.</p>";
        return;
    }

    topicsCache.forEach(t => {

        const div = document.createElement("div");
        div.className = "topic-item";

        const hasStudent = !!selectedStudent;

        /* =========================
           ΕΝΕΡΓΗ ΔΙΠΛΩΜΑΤΙΚΗ
           ========================= */
        if (t.katastash === "Ενεργή") {
            div.classList.add("active");

            div.innerHTML = `
                <div class="topic-top">
                    <h4 class="topic-title">${t.thema}</h4>
                </div>

                <p class="topic-desc">${t.perigrafh ?? ""}</p>

                <div class="active-student-box">
                    <strong> ${t.foititis}</strong>
                     ΑΜ: ${t.am}
                </div>
            `;

            container.appendChild(div);
            return; // ⬅️ σταματά εδώ, καμία ενέργεια
        }


        /* =========================
   ΥΠΟ ΕΞΕΤΑΣΗ
   ========================= */
if (t.katastash === "Υπό εξέταση") {
    div.classList.add("under-review");

    div.innerHTML = `
        <div class="topic-top">
            <h4 class="topic-title">${t.thema}</h4>
        </div>

        <p class="topic-desc">${t.perigrafh ?? ""}</p>

        <div class="under-review-student-box">
             <strong>${t.foititis}</strong><br>
            ΑΜ: ${t.am}<br>
        </div>
    `;

    container.appendChild(div);
    return; // ⛔ σταματά εδώ – ΚΑΜΙΑ ενέργεια
}


        /* =========================
           ΜΗ ΕΝΕΡΓΗ ΘΕΜΑΤΑ
           ========================= */
        let actionHtml = "";

        // κουμπιά εμφανίζονται ΜΟΝΟ αν έχει αναζητηθεί φοιτητής
        if (hasStudent) {

    // ❌ ΕΝΕΡΓΗ: καμία ενέργεια
    if (t.katastash === "Ενεργή") {
        actionHtml = "";
    }

    // ✅ ΠΡΟΣΩΡΙΝΗ ΚΑΤΟΧΥΡΩΣΗ: επιτρέπεται ακύρωση
    else if (t.katastash === "Προσωρινή Κατοχύρωση") {
        actionHtml = `
            <button class="btn btn-danger cancel"
                    data-id="${t.diplwmatikh_id}">
                Ακύρωση
            </button>
        `;
    }

    // ➕ ΜΗ ΑΝΑΤΕΘΕΙΜΕΝΗ: ανάθεση μόνο αν ο φοιτητής δεν είναι locked
    else if (!studentLocked) {
        actionHtml = `
            <button class="btn btn-assign assign"
                    data-id="${t.diplwmatikh_id}">
                Ανάθεση
            </button>
        `;
    }

    // 🔒 αλλιώς: τίποτα
    else {
        actionHtml = "";
    }
}

        div.innerHTML = `
            <div class="topic-top">
                <h4 class="topic-title">${t.thema}</h4>
                <div class="topic-actions">
                    ${actionHtml}
                </div>
            </div>

            <p class="topic-desc">${t.perigrafh ?? ""}</p>

            ${hasStudent ? `<span class="pill">${t.katastash ?? "Μη Ανατεθειμένη"}</span>` : ""}
        `;

        container.appendChild(div);
    });
}


/* ======================================================
   Αναζήτηση Φοιτητή
   ====================================================== */
document.getElementById("studentForm").addEventListener("submit", e => {
    e.preventDefault();

    fetch("search_student.php", {
        method: "POST",
        body: new URLSearchParams({
            search_term: e.target.search_term.value
        })
    })
    .then(r => r.json())
    .then(d => {
        const box = document.getElementById("studentBox");

        if (d.status !== "success") {
            selectedStudent = null;
            studentLocked = false;
            box.innerHTML = d.message;
            renderTopics();
            return;
        }

        selectedStudent = d.student;
       studentLocked = d.has_active_topic === true || d.has_under_review_topic === true;

        let html = `
            <b>${d.student.Plhres_onoma}</b><br>
            ΑΜ: ${d.student.am}
        `;

        if (d.has_under_review_topic) {
    html += `
        <div style="
            margin-top:8px;
            padding:8px;
            background:#fff1f2;
            border:1px solid #fecdd3;
            color:#9f1239;
            border-radius:8px;
            font-size:13px">
             Ο φοιτητής έχει διπλωματική <b>ΥΠΟ ΕΞΕΤΑΣΗ</b><br>
            Δεν επιτρέπεται νέα ανάθεση
        </div>
    `;
}
else if (d.has_active_topic) {
    html += `
        <div style="
            margin-top:8px;
            padding:8px;
            background:#fee2e2;
            border:1px solid #fecaca;
            color:#991b1b;
            border-radius:8px;
            font-size:13px">
            ⚠ Ο φοιτητής έχει ήδη <b>ΕΝΕΡΓΗ</b> διπλωματική
        </div>
    `;
}



        box.innerHTML = html;
        renderTopics();
    })
    .catch(() => alert("Σφάλμα αναζήτησης φοιτητή"));
});

/* ======================================================
   Ανάθεση / Ακύρωση
   ====================================================== */
document.addEventListener("click", e => {

    /* ---------- Ανάθεση ---------- */
    if (e.target.classList.contains("assign")) {

        fetch("assign_topic.php", {
            method: "POST",
            body: new URLSearchParams({
                topic_id: e.target.dataset.id,
                student_id: selectedStudent.mathites_id
            })
        })
        .then(r => r.json())
        .then(resp => {
            if (resp.status !== "success") {
                alert(resp.message || "Αποτυχία ανάθεσης");
                return;
            }

            /* 🔒 κλείδωμα φοιτητή */
            studentLocked = true;
            loadTopics();
        });
    }

    /* ---------- Ακύρωση ---------- */
    if (e.target.classList.contains("cancel")) {
        if (!confirm("Θέλεις σίγουρα ακύρωση ανάθεσης;")) return;

        fetch("cancel_assignment.php", {
            method: "POST",
            body: new URLSearchParams({
                topic_id: e.target.dataset.id
            })
        })
        .then(r => r.json())
        .then(resp => {
            if (resp.status !== "success") {
                alert(resp.message || "Σφάλμα ακύρωσης");
                return;
            }

            studentLocked = false;
            loadTopics();
        });
    }
});

