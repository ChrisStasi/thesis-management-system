console.log("INVITES JS LOADED");

const API_GET_LECTURERS = "get_kathigites.php";
const API_SEND_INVITE  = "apostoli_prosklisis.php";
const API_GET_THESIS   = "apothikefsi_pliroforiwn.php";

document.addEventListener("DOMContentLoaded", () => {

  const inviteCard = document.getElementById("invite-card");
  const lockedSlot = document.getElementById("invite-locked-slot");

  /* ===============================
     ΠΡΩΤΑ: ΕΛΕΓΧΟΣ ΚΑΤΑΣΤΑΣΗΣ (AJAX)
     =============================== */
  fetch(API_GET_THESIS)
    .then(r => r.json())
    .then(data => {

      console.log("THESIS RESPONSE:", data);

      // 🛡️ ΑΣΦΑΛΕΙΑ: άκυρο response
      if (!data || typeof data !== "object") {
        console.warn("Άκυρο response από server");
        enableInvites();
        return;
      }

      // 🛡️ Δεν υπάρχει διπλωματική
      if (!data.success || !data.thesis) {
        console.warn("Δεν βρέθηκε διπλωματική");
        enableInvites();
        return;
      }

      const katastash = (data.thesis.katastash || "").trim();
      console.log("KATASTASH FROM DB:", katastash);

      /* ===============================
         ΜΟΝΟ ΑΝ ΕΙΝΑΙ ΕΝΕΡΓΗ
         =============================== */
      if (katastash === "Ενεργή") {

        // ❌ Κρύψε φόρμα προσκλήσεων
        if (inviteCard) inviteCard.style.display = "none";

        // ✅ Εμφάνισε ΜΟΝΟ το μήνυμα
        if (lockedSlot) {
          lockedSlot.style.display = "block";
          lockedSlot.innerHTML = `
            <div class="invitation-locked success">
              <div class="invitation-icon">✓</div>
              <div class="invitation-text">
                <strong>Η τριμελής επιτροπή έχει ολοκληρωθεί.</strong><br>
                Δύο διδάσκοντες έχουν αποδεχθεί την πρόσκληση και η διπλωματική
                βρίσκεται πλέον σε κατάσταση <em>Ενεργή</em>.
              </div>
            </div>
          `;
        }

        return; // ⛔ ΤΕΛΟΣ — δεν ενεργοποιούμε προσκλήσεις
      }
 if (katastash === "Υπό εξέταση") {
      if (inviteCard) inviteCard.style.display = "none";

      renderUnderReviewUI();   // 🔥 ΑΥΤΟ ΕΛΕΙΠΕ
      loadUnderReviewData();   // 🔥 ΚΑΙ ΑΥΤΟ

      return;
    }
      /* ===============================
         ΟΛΕΣ ΟΙ ΑΛΛΕΣ ΚΑΤΑΣΤΑΣΕΙΣ
         =============================== */
      enableInvites();
    })
    .catch(err => {
      console.error("Σφάλμα φόρτωσης κατάστασης:", err);
      enableInvites(); // fallback
    });

  /* ===============================
     ΕΝΕΡΓΟΠΟΙΗΣΗ ΠΡΟΣΚΛΗΣΕΩΝ
     =============================== */
  function enableInvites() {
    loadLecturers();
    setupInvite();
  }
});

/* ===============================
   ΦΟΡΤΩΣΗ ΔΙΔΑΣΚΟΝΤΩΝ
   =============================== */
function loadLecturers() {
  const select = document.getElementById("lecturer-select");
  if (!select) return;

  console.log("loadLecturers");

  select.innerHTML = `<option value="">Φόρτωση...</option>`;

  fetch(API_GET_LECTURERS)
    .then(r => r.json())
    .then(data => {
      select.innerHTML = `<option value="">— Επιλέξτε διδάσκοντα —</option>`;
      (data.lecturers || []).forEach(l => {
        const opt = document.createElement("option");
        opt.value = l.kathigites_id;
        opt.textContent = l.Plhres_onoma;
        select.appendChild(opt);
      });
    })
    .catch(() => {
      select.innerHTML = `<option value="">Σφάλμα φόρτωσης</option>`;
    });
}

/* ===============================
   ΠΡΟΣΚΛΗΣΗ ΜΕΛΟΥΣ
   =============================== */
function setupInvite() {
    const select = document.getElementById("lecturer-select");
    const btn    = document.getElementById("inviteBtn");
    const msg    = document.getElementById("invitation-message");

    btn.addEventListener("click", () => {
        const id = select.value;
        if (!id) {
            msg.textContent = "Επιλέξτε καθηγητή.";
            return;
        }

        fetch(API_SEND_INVITE, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ kathigitis_id: parseInt(id, 10) })
        })
        .then(r => r.json())
        .then(res => {
            msg.textContent = res.message || "Ολοκληρώθηκε.";
            if (res.success) setTimeout(() => location.reload(), 800);
        });
    });
}

function loadLecturers() {
    const select = document.getElementById("lecturer-select");
    select.innerHTML = `<option value="">Φόρτωση...</option>`;

    fetch(API_GET_LECTURERS)
        .then(r => r.json())
        .then(data => {
            select.innerHTML = `<option value="">-- Επιλέξτε --</option>`;
            data.lecturers.forEach(l => {
                const opt = document.createElement("option");
                opt.value = l.kathigites_id;
                opt.textContent = l.Plhres_onoma;
                select.appendChild(opt);
            });
        });
}

/* ===============================
   ΥΠΟ ΕΞΕΤΑΣΗ – SECTIONS
   =============================== */
function renderUnderReviewUI() {
    const slot = document.getElementById("under-review-sections");
    if (!slot) return;
    slot.innerHTML = `
      <div class="card">
        <h2 class="section-title">Υπό Εξέταση</h2>

        <div class="ur-block">
          <h3>📄 Πρόχειρο Κείμενο</h3>
           <input type="file" id="draftFile" accept=".pdf,.doc,.docx">

  <button type="button" onclick="uploadDraft()">
    Ανάρτηση
  </button>
          <div id="draft-preview"></div>
        </div>

        <div class="ur-block">
          <h3>🔗 Σύνδεσμοι Υλικού</h3>
          <input type="text" id="materialLinks" placeholder="Google Drive / YouTube">
          <button onclick="saveLinks()">Αποθήκευση</button>
           <div id="link-preview"></div>
        </div>

        <div class="ur-block">
          <h3>🗓️ Στοιχεία Εξέτασης</h3>
          <input type="datetime-local" id="examDatetime">
          <select id="examType">
            <option value="Δια ζώσης">Δια ζώσης</option>
            <option value="Διαδικτυακά">Διαδικτυακά</option>
          </select>
          <input type="text" id="examLocation" placeholder="Αίθουσα ή σύνδεσμος">
          <button onclick="saveExamInfo()">Αποθήκευση</button>
          <p id="examMsg"></p>
        </div>

        <div class="ur-block" id="praktiko-box">
  <h3>📜 Πρακτικό Εξέτασης</h3>
  <p class="muted">Αναμονή ολοκλήρωσης βαθμολόγησης.</p>
</div>


        <div class="ur-block" id="nimertis-box">
  <h3>📚 Νημερτής</h3>
  <!-- dynamic -->
</div>

    `;
}

function saveExamInfo() {
    const datetimeInput = document.getElementById("examDatetime");
    const typeSelect    = document.getElementById("examType");
    const locationInput = document.getElementById("examLocation");

    const hmera_wra  = datetimeInput.value.trim();
    const typos      = typeSelect.value;
    const topothesia = locationInput.value.trim();

    /* ===============================
       CLIENT-SIDE VALIDATION
       =============================== */

    if (!hmera_wra) {
        showSaveMessage("Συμπλήρωσε ημερομηνία και ώρα εξέτασης", "error");
        return;
    }

    if (!topothesia) {
        showSaveMessage(
            typos === "Δια ζώσης"
                ? "Συμπλήρωσε αίθουσα εξέτασης"
                : "Συμπλήρωσε σύνδεσμο (Zoom / Teams)",
            "error"
        );
        return;
    }

    // Δια ζώσης → ΔΕΝ επιτρέπεται link
    if (typos === "Δια ζώσης" && /^https?:\/\//i.test(topothesia)) {
        showSaveMessage("Στη Δια ζώσης επιτρέπεται μόνο αίθουσα", "error");
        return;
    }

    // Διαδικτυακά → ΜΟΝΟ link
    if (typos === "Διαδικτυακά" && !/^https?:\/\//i.test(topothesia)) {
        showSaveMessage("Στα διαδικτυακά απαιτείται έγκυρος σύνδεσμος", "error");
        return;
    }

    /* ===============================
       SAVE (AJAX)
       =============================== */
    fetch("plirofories_eksetasis.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            diplwmatikh_id: DIPLOMATIKI_ID,
            hmera_wra: hmera_wra,
            typos: typos,
            topothesia: topothesia
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showSaveMessage("✔ Τα στοιχεία εξέτασης αποθηκεύτηκαν");

            // 🔥 ΕΠΑΝΑΦΟΡΤΩΣΗ ΔΕΔΟΜΕΝΩΝ ΑΠΟ ΒΔ
            loadUnderReviewData();
        } else {
            // Μηνύματα από PHP validation
            if (res.error === "invalid_room") {
                showSaveMessage("Στη Δια ζώσης επιτρέπεται μόνο αίθουσα", "error");
            } else if (res.error === "invalid_link") {
                showSaveMessage("Στα διαδικτυακά απαιτείται έγκυρος σύνδεσμος", "error");
            } else {
                showSaveMessage("Σφάλμα αποθήκευσης στοιχείων εξέτασης", "error");
            }
        }
    })
    .catch(() => {
        showSaveMessage("Σφάλμα επικοινωνίας με τον διακομιστή", "error");
    });
}






function uploadDraft() {
    const fileInput = document.getElementById("draftFile");
    if (!fileInput || !fileInput.files.length) {
        showSaveMessage("Επίλεξε αρχείο πρώτα", "error");
        return;
    }

    const fd = new FormData();
    fd.append("diplwmatikh_id", DIPLOMATIKI_ID);
    fd.append("file", fileInput.files[0]);

    fetch("proxeiro.php", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showSaveMessage("✔ Το πρόχειρο αρχείο ανέβηκε");

            // ✅ ΑΜΕΣΗ ΕΜΦΑΝΙΣΗ ΣΤΗ ΣΕΛΙΔΑ
            showDraftPreview(res.filename);

            // καθάρισε το input
            fileInput.value = "";
            loadUnderReviewData();
        } else {
            showSaveMessage(res.error || "Σφάλμα ανεβάσματος", "error");
        }
    })
    .catch(() => {
        showSaveMessage("Σφάλμα δικτύου", "error");
    });
}


function showDraftPreview(filename) {
    const box = document.getElementById("draft-preview");
    if (!box) return;

    const url = `/foititis/uploads/${filename}`;

    box.innerHTML = `
        <div class="draft-file">
            📄 <a href="${url}" target="_blank">${filename}</a>
            <button type="button"
                    class="btn-delete-draft"
                    onclick="deleteDraft()">
                 Διαγραφή
            </button>
        </div>
    `;
}




function deleteDraft() {
    if (!confirm("Θέλεις σίγουρα να διαγράψεις το πρόχειρο αρχείο;")) return;

    fetch("delete_proxeiro.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ diplwmatikh_id: DIPLOMATIKI_ID })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showSaveMessage(" Το πρόχειρο διαγράφηκε");
            const box = document.getElementById("draft-preview");
            if (box) box.innerHTML = "";
        } else {
            showSaveMessage(res.error || "Σφάλμα διαγραφής", "error");
        }
    });
}








function saveLinks() {
    const linkInput = document.getElementById("materialLinks");
    const link = linkInput.value.trim();

    if (!link) {
        showSaveMessage("Συμπλήρωσε σύνδεσμο", "error");
        return;
    }

    fetch("sindesmoi.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            diplwmatikh_id: DIPLOMATIKI_ID,
            link: link
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showSaveMessage("✔ Ο σύνδεσμος αποθηκεύτηκε");

            // ✅ ΑΜΕΣΗ ΕΜΦΑΝΙΣΗ
            showLinkPreview(link);

            // καθάρισε input
            linkInput.value = "";

            // συγχρονισμός με ΒΔ (για refresh)
            loadUnderReviewData();
        } else {
            showSaveMessage(res.error || "Σφάλμα αποθήκευσης", "error");
        }
    })
    .catch(() => {
        showSaveMessage("Σφάλμα δικτύου", "error");
    });
}





function showLinkPreview(link) {
    const box = document.getElementById("link-preview");
    if (!box) return;

    box.innerHTML = `
        <div class="draft-file">
            🔗 <a href="${link}" target="_blank" rel="noopener">
                ${link}
            </a>

            <button type="button"
                    class="btn-delete-draft"
                    onclick="deleteLink()">
                 Διαγραφή
            </button>
        </div>
    `;
}



function deleteLink() {
    if (!confirm("Θέλεις σίγουρα να διαγράψεις τον σύνδεσμο;")) return;

    fetch("delete_link.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            diplwmatikh_id: DIPLOMATIKI_ID
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showSaveMessage(" Ο σύνδεσμος διαγράφηκε");
            document.getElementById("link-preview").innerHTML = "";
        } else {
            showSaveMessage(res.error || "Σφάλμα διαγραφής", "error");
        }
    });
}





function showSaveMessage(text, type = "success") {
    const box = document.getElementById("save-feedback");
    if (!box) return;

    box.textContent = text;
    box.className = `save-feedback ${type}`;
    box.style.display = "block";

    // fade in
    requestAnimationFrame(() => {
        box.style.opacity = "1";
    });

    // fade out μετά από 2.5s
    setTimeout(() => {
        box.style.opacity = "0";
        setTimeout(() => {
            box.style.display = "none";
        }, 300);
    }, 2500);
}



function loadUnderReviewData() {
  fetch("apothikefsi_pliroforiwn.php")
    .then(r => r.json())
    .then(data => {
      if (!data || !data.success || !data.thesis) return;

      const thesis = data.thesis;

      // 🔗 Link
      if (thesis.link) {
        showLinkPreview(thesis.link);
      } else {
        const box = document.getElementById("link-preview");
        if (box) box.innerHTML = "";
      }

      // 📄 Πρόχειρο αρχείο
      if (thesis.proxeiro_arxeio) {
        showDraftPreview(thesis.proxeiro_arxeio);
      } else {
        const box = document.getElementById("draft-preview");
        if (box) box.innerHTML = "";
      }

      // 🗓️ (αν έχεις αυτά στη ΒΔ)
      // 🗓️ Ημερομηνία & ώρα
if (thesis.hmera_wra_parousiashs && document.getElementById("examDatetime")) {
  document.getElementById("examDatetime").value =
    thesis.hmera_wra_parousiashs.replace(" ", "T").slice(0, 16);
}

// 🧭 Τύπος
if (thesis.typos_parousiashs && document.getElementById("examType")) {
  document.getElementById("examType").value = thesis.typos_parousiashs;
}

// 🏫 Τοποθεσία
if (thesis.topothesia_parousiashs && document.getElementById("examLocation")) {
  document.getElementById("examLocation").value = thesis.topothesia_parousiashs;
}

// ===============================
// ΠΡΑΚΤΙΚΟ ΕΞΕΤΑΣΗΣ
// ===============================
if (data.praktiko?.ready) {
  renderPraktikoHTML(data);
}

if (data.nimertis) {
  renderNimertisUI(data.nimertis);
}

    })
    .catch(err => console.error("loadUnderReviewData error:", err));
}




function renderPraktikoHTML(data) {
  const box = document.getElementById("praktiko-box");
  if (!box) return;

  const t = data.thesis;
  const p = data.praktiko;

  const dateObj = new Date(t.hmera_wra_parousiashs);
  const ημερομηνια = dateObj.toLocaleDateString("el-GR");
  const ωρα = dateObj.toLocaleTimeString("el-GR", { hour: '2-digit', minute: '2-digit' });

  box.innerHTML = `
<div class="praktiko">

  <h1>ΠΡΟΓΡΑΜΜΑ ΣΠΟΥΔΩΝ</h1>
  <h2>«ΤΜΗΜΑΤΟΣ ΜΗΧΑΝΙΚΩΝ, ΗΛΕΚΤΡΟΝΙΚΩΝ ΥΠΟΛΟΓΙΣΤΩΝ ΚΑΙ ΠΛΗΡΟΦΟΡΙΚΗΣ»</h2>

  <h3>ΠΡΑΚΤΙΚΟ ΣΥΝΕΔΡΙΑΣΗΣ<br>
  ΤΗΣ ΤΡΙΜΕΛΟΥΣ ΕΠΙΤΡΟΠΗΣ</h3>

  <p class="center">
    ΓΙΑ ΤΗΝ ΠΑΡΟΥΣΙΑΣΗ ΚΑΙ ΚΡΙΣΗ ΤΗΣ ΔΙΠΛΩΜΑΤΙΚΗΣ ΕΡΓΑΣΙΑΣ
  </p>

  <p>
    του/της φοιτητή/τριας <strong>${t.foititis}</strong>
  </p>

  <p>
    Η συνεδρίαση πραγματοποιήθηκε
    ${t.typos_parousiashs === "Διά Ζώσης"
      ? `στην αίθουσα <strong>${t.topothesia_parousiashs}</strong>`
      : `διαδικτυακά (<strong>${t.topothesia_parousiashs}</strong>)`
    },
    στις <strong>${ημερομηνια}</strong> και ώρα <strong>${ωρα}</strong>.
  </p>

  <p>Στην συνεδρίαση είναι παρόντα τα μέλη της Τριμελούς Επιτροπής:</p>

  <ol>
    ${p.grades.map(g => `<li>${g.Plhres_onoma}</li>`).join("")}
  </ol>

  <p>
    Ο/Η φοιτητής/τρια ανέπτυξε το θέμα της Διπλωματικής του/της Εργασίας
    με τίτλο:
  </p>

  <p class="title">
    «${t.thema}»
  </p>

  <p>
    Μετά το τέλος της ανάπτυξης της εργασίας και των ερωτήσεων,
    ο υποψήφιος αποχωρεί.
  </p>

  <p>
    Τα μέλη της Τριμελούς Επιτροπής ψηφίζουν υπέρ της εγκρίσεως
    της Διπλωματικής Εργασίας.
  </p>

  <p>
    Μετά την έγκριση, απονέμεται στον/στη φοιτητή/τρια
    <strong>${t.foititis}</strong>
    ο βαθμός:
  </p>

  <h2 class="final-grade">${p.average}</h2>

  <table class="grades-table">
    <thead>
      <tr>
        <th>ΟΝΟΜΑΤΕΠΩΝΥΜΟ</th>
        <th>ΒΑΘΜΟΣ</th>
      </tr>
    </thead>
    <tbody>
      ${p.grades.map(g => `
        <tr>
          <td>${g.Plhres_onoma}</td>
          <td>${g.telikos_vathmos}</td>
        </tr>
      `).join("")}
    </tbody>
  </table>

  <p class="footer">
    Η Τριμελής Επιτροπή προτείνει την ανακήρυξη του φοιτητή
    σε διπλωματούχο του ΤΜΗΥΠ Πανεπιστημίου Πατρών.
  </p>

</div>
`;
}


function renderNimertisUI(nimertis) {
  const box = document.getElementById("nimertis-box");
  if (!box) return;

  // 🟡 Δεν επιτρέπεται ακόμη
  if (!nimertis.allowed) {
    box.innerHTML += `
      <p class="muted">
        ℹ️ Η καταχώρηση συνδέσμου Νημερτής
        θα ενεργοποιηθεί μετά την υποβολή όλων των βαθμών.
      </p>
    `;
    return;
  }

  // 🔵 Έχει ήδη καταχωρηθεί
  if (nimertis.submitted) {
    box.innerHTML += `
      <p class="success">
        ✔️ Ο σύνδεσμος Νημερτής έχει καταχωρηθεί
      </p>
      <a href="${nimertis.url}" target="_blank">
        🔗 Άνοιγμα αποθετηρίου
      </a>
    `;
    return;
  }

  // 🟢 Επιτρέπεται αλλά δεν έχει καταχωρηθεί
  box.innerHTML += `
    <input type="url"
           id="nimertisLink"
           placeholder="https://nimertis.uop.gr/handle/..."
           required>
    <button onclick="saveNimertis()">Αποθήκευση</button>
  `;
}

function saveNimertis() {
  const input = document.getElementById("nimertisLink");
  if (!input || !input.value.trim()) {
    showSaveMessage("Συμπλήρωσε σύνδεσμο Νημερτής", "error");
    return;
  }

  fetch("ajax/save_nimertis.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ url: input.value.trim() })
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      showSaveMessage("✔ Ο σύνδεσμος Νημερτής αποθηκεύτηκε");
      loadUnderReviewData(); // 🔄 refresh
    } else {
      showSaveMessage(res.message || "Σφάλμα", "error");
    }
  });
}
