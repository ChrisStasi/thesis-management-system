/* ============================================================
   details.js – ΤΕΛΙΚΗ ΣΤΑΘΕΡΗ ΕΚΔΟΣΗ (STYLE SAFE)
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {
  if (!window.DIPLO_ID) {
    showError('Δεν ορίστηκε διπλωματική');
    return;
  }
  loadDetails();
});

let canAddNotes = false;
let isActiveDiploma = false;

/* ============================================================
   ΦΟΡΤΩΣΗ ΛΕΠΤΟΜΕΡΕΙΩΝ
   ============================================================ */
function loadDetails() {
  fetch(`/didaskwn/lista/api/get_details.php?id=${window.DIPLO_ID}`)
    .then(res => {
      if (!res.ok) throw new Error();
      return res.json();
    })
    .then(data => {
      if (data.error) {
        showError(data.error);
        return;
      }

      hideError();
      hideLoading();
      document.getElementById('cancel-area').innerHTML = '';

      isActiveDiploma = data.diplwmatikh.katastash === 'Ενεργή';
      canAddNotes = isActiveDiploma && (data.is_epivlepwn || data.is_trimelhs);

      renderBasicInfo(data.diplwmatikh);
      renderCommittee(data);
      renderInvites(data);
      renderNotesSection(data);
      renderGradesSection(data);
      renderDraftUnderReview(data);
      renderPresentationAnnouncement(data);
      renderEnableGradingButton(data);
      renderGradingSection(data);



      renderCancelAssignment(data);
      renderUnderReviewButton(data);
      renderCancelActiveDiploma(data);

      loadNotes();
    })
    .catch(() => showError('Σφάλμα επικοινωνίας με τον server'));
}

/* ============================================================
   ΒΑΣΙΚΑ ΣΤΟΙΧΕΙΑ
   ============================================================ */
function renderBasicInfo(d) {
  document.getElementById('basic-info').innerHTML = `
    <h2>Βασικά Στοιχεία</h2>
    <p><strong>Θέμα:</strong> ${escapeHTML(d.thema)}</p>
    <p><strong>Φοιτητής:</strong> ${escapeHTML(d.foititis ?? '—')}</p>
    <p><strong>Επιβλέπων:</strong> ${escapeHTML(d.epivlepwn)}</p>
    <p><strong>Κατάσταση:</strong> ${escapeHTML(d.katastash)}</p>
  `;
}

/* ============================================================
   ΤΡΙΜΕΛΗΣ
   ============================================================ */
function renderCommittee(data) {
  if (!['Ενεργή', 'Υπό εξέταση'].includes(data.diplwmatikh.katastash)) return;

  const el = document.getElementById('committee');
  el.style.display = 'block';

  if (!data.trimelhs_melh?.length) {
    el.innerHTML = `<h2>Τριμελής Επιτροπή</h2><p>Δεν έχουν οριστεί μέλη.</p>`;
    return;
  }

  el.innerHTML = `
    <h2>Τριμελής Επιτροπή</h2>
    <ul class="committee-list">
      ${data.trimelhs_melh.map(m => `
        <li class="committee-member">${escapeHTML(m.Plhres_onoma)}</li>
      `).join('')}
    </ul>
  `;
}

/* ============================================================
   ΠΡΟΣΚΛΗΣΕΙΣ
   ============================================================ */
function renderInvites(data) {
  if (!data.is_under_assignment) return;

  const el = document.getElementById('invites');
  el.style.display = 'block';

  if (!data.proskliseis?.length) {
    el.innerHTML = `<h2>Προσκλήσεις Τριμελούς</h2><p>Δεν υπάρχουν προσκλήσεις.</p>`;
    return;
  }

  el.innerHTML = `
    <h2>Προσκλήσεις Τριμελούς</h2>
    <div class="invites-wrapper">
      ${data.proskliseis.map(p => `
        <div class="invite-card">
          <div class="invite-header">
            <span class="invite-name">${escapeHTML(p.Plhres_onoma)}</span>
            <span class="status ${inviteClass(p.apanthsh)}">
              ${inviteText(p.apanthsh)}
            </span>
          </div>
        </div>
      `).join('')}
    </div>
  `;
}

/* ============================================================
   ΣΗΜΕΙΩΣΕΙΣ
   ============================================================ */
function renderNotesSection(data) {
  if (!canAddNotes) return;

  const el = document.getElementById('notes');
  el.style.display = 'block';

  el.innerHTML = `
    <div class="notes-card">
      <h2 class="notes-title">📝 Σημειώσεις Διπλωματικής</h2>
      <textarea id="note-text" maxlength="300"
        placeholder="Γράψε εδώ μια σύντομη σημείωση (έως 300 χαρακτήρες)..."></textarea>
      <div class="notes-actions">
        <span class="char-limit">Μέγιστο: 300 χαρακτήρες</span>
        <button type="button" class="btn-save-note" id="save-note-btn">
          Αποθήκευση
        </button>
      </div>
    </div>
  `;

  document.getElementById('save-note-btn')
    .addEventListener('click', saveNote);
}

function saveNote() {
  const keimeno = document.getElementById('note-text').value.trim();
  if (!keimeno) return alert('Η σημείωση είναι κενή');

  fetch('/didaskwn/lista/api/apothikefsisimeiwsis.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ diplwmatikh_id: window.DIPLO_ID, keimeno })
  })
    .then(res => res.json())
    .then(() => {
      document.getElementById('note-text').value = '';
      loadNotes();
    });
}

/* ============================================================
   ΛΙΣΤΑ ΣΗΜΕΙΩΣΕΩΝ
   ============================================================ */
function loadNotes() {
  if (!canAddNotes) return;

  fetch(`/didaskwn/lista/api/get_notes.php?diplwmatikh_id=${window.DIPLO_ID}`)
    .then(res => res.json())
    .then(renderNotesList);
}

function renderNotesList(notes) {
  const el = document.getElementById('notes-list');
  if (!notes?.length) {
    el.innerHTML = `<p style="margin-top:15px;color:#6b7280;">Δεν υπάρχουν σημειώσεις.</p>`;
    return;
  }

  el.innerHTML = `
    <h3 style="margin-top:30px;">📌 Καταχωρημένες Σημειώσεις</h3>
    ${notes.map(n => `
      <div class="note-item">
        <div class="note-header">
          <strong>${escapeHTML(n.kathigitis)}</strong>
          <span>${formatDate(n.hmeromhnia)}</span>
        </div>
        <div class="note-body">${escapeHTML(n.keimeno)}</div>
        <div class="note-actions">
          <button class="btn-delete-note" onclick="deleteNote(${n.shmeiwsh_id})">
            Διαγραφή
          </button>
        </div>
      </div>
    `).join('')}
  `;
}

function deleteNote(id) {
  if (!confirm('Θέλεις σίγουρα να διαγράψεις τη σημείωση;')) return;

  fetch('/didaskwn/lista/api/delete_note.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ shmeiwsh_id: id })
  }).then(loadNotes);
}

/* ============================================================
   ΑΚΥΡΩΣΗ ΠΡΙΝ ΤΗΝ ΕΝΕΡΓΟΠΟΙΗΣΗ
   ============================================================ */
function renderCancelAssignment(data) {
  const area = document.getElementById('cancel-area');
  if (!data.is_epivlepwn) return;
  if (!['Υπό ανάθεση', 'Προσωρινή Κατοχύρωση'].includes(data.diplwmatikh.katastash)) return;

  area.style.display = 'block';
  area.innerHTML = `
    <button class="btn-cancel" onclick="cancelAssignment()">
      Ακύρωση Ανάθεσης
    </button>
  `;
}

function cancelAssignment() {
  if (!confirm('Θέλεις σίγουρα να ακυρώσεις την ανάθεση;')) return;

  fetch('/didaskwn/lista/api/akirosianathesis.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ diplwmatikh_id: window.DIPLO_ID })
  })
    .then(res => res.json())
    .then(loadDetails);
}

/* ============================================================
   ΘΕΣΗ ΣΕ ΥΠΟ ΕΞΕΤΑΣΗ
   ============================================================ */
function renderUnderReviewButton(data) {
  const area = document.getElementById('cancel-area');
  if (!data.is_epivlepwn) return;
  if (data.diplwmatikh.katastash !== 'Ενεργή') return;

  area.style.display = 'block';
  area.innerHTML += `
    <button class="btn-save-note" onclick="setUnderReview()">
      Αλλαγή σε Υπό Εξέταση
    </button>
  `;
}

function setUnderReview() {
  if (!confirm('Να αλλάξει η κατάσταση της διπλωματικής σε «Υπό Εξέταση»;')) {
    return;
  }

  fetch('/didaskwn/lista/api/ypo_eksetasi.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      diplwmatikh_id: window.DIPLO_ID
    })
  })
    .then(res => res.json())
    .then(res => {
      if (res.error) {
        alert(res.error);
        return;
      }
      loadDetails();
    })
    .catch(() => {
      alert('Σφάλμα επικοινωνίας με τον server');
    });
}


/* ============================================================
   ΑΚΥΡΩΣΗ ΕΝΕΡΓΗΣ (2 ΕΤΗ + ΓΣ)
   ============================================================ */
function renderCancelActiveDiploma(data) {
  const area = document.getElementById('cancel-area');
  if (!data.is_epivlepwn) return;
  if (data.diplwmatikh.katastash !== 'Ενεργή') return;

  area.style.display = 'block';
  area.innerHTML += `
    <button class="btn-cancel" onclick="cancelActiveDiploma()">
      Ακύρωση Ενεργής Διπλωματικής
    </button>
  `;
}

function cancelActiveDiploma() {
  if (!confirm('Η ακύρωση ενεργής διπλωματικής απαιτεί απόφαση ΓΣ. Συνέχεια;')) {
    return;
  }

  const gs_arithmos = prompt('Αριθμός Γενικής Συνέλευσης:');
  const gs_etos = prompt('Έτος Γενικής Συνέλευσης:');

  if (!gs_arithmos || !gs_etos) {
    alert('Απαιτούνται στοιχεία Γενικής Συνέλευσης');
    return;
  }

  fetch('/didaskwn/lista/api/akirosienergis.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      diplwmatikh_id: window.DIPLO_ID,
      gs_arithmos,
      gs_etos
    })
  })
    .then(res => res.json())          // ⬅️ ΠΑΡΕ ΤΗΝ ΑΠΑΝΤΗΣΗ
    .then(res => {
      if (res.error) {                // ⬅️ ΕΛΕΓΧΟΣ ΣΦΑΛΜΑΤΟΣ
        alert(res.error);
        return;
      }
      loadDetails();                  // ⬅️ ΜΟΝΟ ΑΝ ΠΕΤΥΧΕ
    })
    .catch(() => {
      alert('Σφάλμα επικοινωνίας με τον server');
    });
}

/* ============================================================
   HELPERS
   ============================================================ */
function escapeHTML(str) {
  if (!str) return '';
  return str.replace(/[&<>"']/g, c =>
    ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c])
  );
}

function inviteClass(ans) {
  if (ans === 'Δεκτή') return 'accepted';
  if (ans === 'Απορρίπτεται') return 'rejected';
  return 'pending';
}

function inviteText(ans) {
  if (ans === 'Δεκτή') return 'Αποδεκτή';
  if (ans === 'Απορρίπτεται') return 'Απορρίφθηκε';
  return 'Εκκρεμεί';
}

function showError(msg) {
  const el = document.getElementById('error');
  el.style.display = 'block';
  el.innerText = msg;
}

function hideError() {
  const el = document.getElementById('error');
  el.style.display = 'none';
  el.innerText = '';
}

function hideLoading() {
  const el = document.getElementById('loading');
  if (el) el.style.display = 'none';
}

function formatDate(dateStr) {
  const d = new Date(dateStr);
  return d.toLocaleDateString('el-GR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

function renderGradesSection() {
  // placeholder
}



/* ============================================================
   ΠΡΟΧΕΙΡΟ ΚΕΙΜΕΝΟ ΦΟΙΤΗΤΗ – ΥΠΟ ΕΞΕΤΑΣΗ (ΟΛΟΙ ΟΙ ΔΙΔΑΣΚΟΝΤΕΣ)
   ============================================================ */
function renderDraftUnderReview(data) {
  const d = data.diplwmatikh;

  // ΜΟΝΟ όταν είναι Υπό εξέταση
  if (d.katastash !== 'Υπό εξέταση') return;

  const el = document.getElementById('notes');
  if (!el) return;

  el.style.display = 'block';

  // Δεν υπάρχει πρόχειρο
  if (!d.proxeiro_arxeio) {
    el.innerHTML = `
      <div class="notes-card">
        <h2>📄 Πρόχειρο Κείμενο Διπλωματικής</h2>
        <p style="color:#6b7280;">Δεν έχει αναρτηθεί πρόχειρο κείμενο.</p>
      </div>
    `;
    return;
  }

  // Υπάρχει πρόχειρο
  el.innerHTML = `
    <div class="notes-card">
      <h2>📄 Πρόχειρο Κείμενο Διπλωματικής</h2>
      <a class="btn-save-note"
         href="/foititis/uploads/${d.proxeiro_arxeio}"
         target="_blank">
        Προβολή Αρχείου
      </a>
    </div>
  `;
}

/* ============================================================
   ΑΝΑΚΟΙΝΩΣΗ ΠΑΡΟΥΣΙΑΣΗΣ – ΜΟΝΟ ΕΠΙΒΛΕΠΩΝ
   ============================================================ */

function renderPresentationAnnouncement(data) {
 
  const d = data.diplwmatikh;

  // ΜΟΝΟ Υπό εξέταση
  if (d.katastash !== 'Υπό εξέταση') return;

  // ΜΟΝΟ επιβλέπων
  if (!data.is_epivlepwn) return;

  // Απαιτούμενα στοιχεία από φοιτητή
  if (!d.hmera_wra_parousiashs || !d.typos_parousiashs || !d.topothesia_parousiashs) {
    return;
  }

  const el = document.getElementById('notes');
  if (!el) return;

  const date = new Date(d.hmera_wra_parousiashs);
  const formattedDate = date.toLocaleString('el-GR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });

  let placeText = '';

  if (d.typos_parousiashs === 'Διά Ζώσης') {
    placeText = `στην αίθουσα ${d.topothesia_parousiashs}`;
  } 
  else if (d.typos_parousiashs === 'Διαδικτυακά') {
    placeText = `διαδικτυακά μέσω του συνδέσμου ${d.topothesia_parousiashs}`;
  } 
  else {
    // ασφάλεια για απροσδιόριστο τύπο
    return;
  }

  const announcementText = `Ανακοινώνεται ότι η παρουσίαση της διπλωματικής εργασίας με τίτλο
«${d.thema}» του/της φοιτητή/τριας ${d.foititis}
θα πραγματοποιηθεί την ${formattedDate},
${placeText}.`;

  el.innerHTML += `
  <div class="notes-card">
    <h2>📢 Ανακοίνωση Παρουσίασης</h2>

    <button class="btn-save-note" id="toggle-announcement-btn">
      Παραγωγή Ανακοίνωσης
    </button>

    <div id="announcement-box" style="display:none; margin-top:12px;">
      <textarea readonly rows="6"
        style="width:100%; resize:none;">${announcementText}</textarea>
    </div>
  </div>
`;

const toggleBtn = document.getElementById('toggle-announcement-btn');
const box = document.getElementById('announcement-box');

toggleBtn.addEventListener('click', () => {
  const isOpen = box.style.display === 'block';

  box.style.display = isOpen ? 'none' : 'block';
  toggleBtn.textContent = isOpen
    ? 'Παραγωγή Ανακοίνωσης'
    : 'Απόκρυψη Ανακοίνωσης';
});

}

function renderEnableGradingButton(data) {
  if (!data.is_epivlepwn) return;
  if (data.diplwmatikh.katastash !== 'Υπό εξέταση') return;
  const gradingEnabled = Number(data.diplwmatikh.energopoihsh_eisodou_vathmou || 0);
if (gradingEnabled === 1) return;


  const area = document.getElementById('cancel-area');
  area.style.display = 'block';

  area.innerHTML += `
    <button class="btn-save-note" onclick="enableGrading()">
      Ενεργοποίηση Καταχώρησης Βαθμών
    </button>
  `;
}


function enableGrading() {
  if (!confirm('Να ενεργοποιηθεί η καταχώρηση βαθμών;')) return;

  fetch('/didaskwn/lista/api/energopoiisi_eisodou_vathmou.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ diplwmatikh_id: window.DIPLO_ID })
  })
  .then(r => r.json())
  .then(res => {
    if (res.error) {
      alert('Δεν επιτρέπεται η ενέργεια');
      return;
    }
    loadDetails(); // refresh UI
  });
}


function renderGradingSection(data) {
  // μόνο σε Υπό Εξέταση
  if (data.diplwmatikh.katastash !== 'Υπό εξέταση') return;

  // μόνο αν ενεργοποιήθηκε από επιβλέποντα
  const gradingEnabled = Number(data.diplwmatikh.energopoihsh_eisodou_vathmou || 0);
if (gradingEnabled !== 1) return;


  // μόνο αν είναι επιβλέπων ή μέλος τριμελούς
  if (!data.is_epivlepwn && !data.is_trimelhs) return;

  const area = document.getElementById('grades');
  area.style.display = 'block';

  area.innerHTML = `
    <div class="grades-card">
      <h2>📊 Βαθμολόγηση Διπλωματικής</h2>

      <p class="muted">
        Η βαθμολόγηση γίνεται βάσει των κριτηρίων του κανονισμού ΔΕ ΤΜΗΥΠ.
      </p>

      <form id="grading-form">
        ${renderGradeInput('poiotita', 'Ποιότητα Δ.Ε. & επίτευξη στόχων (60%)')}
        ${renderGradeInput('xronos', 'Χρονικό διάστημα εκπόνησης (15%)')}
        ${renderGradeInput('keimeno', 'Ποιότητα & πληρότητα κειμένου (15%)')}
        ${renderGradeInput('parousiasi', 'Συνολική εικόνα παρουσίασης (10%)')}

        <button type="submit" class="btn-save-note">
          Αποθήκευση Βαθμών
        </button>
      </form>

      <div id="all-grades"></div>
    </div>
  `;

  document
    .getElementById('grading-form')
    .addEventListener('submit', saveGrades);

  loadAllGrades();
}
function renderGradeInput(key, label) {
  return `
    <div class="grade-row">
      <label>${label}</label>
      <input type="number"
             min="0"
             max="10"
             step="0.5"
             name="${key}"
             required>
    </div>
  `;
}

function saveGrades(e) {
  e.preventDefault();

  const form = document.getElementById('grading-form');
  const data = new FormData(form);

  const payload = {
    diplwmatikh_id: window.DIPLO_ID,
    poiotita: data.get('poiotita'),
    xronos: data.get('xronos'),
    keimeno: data.get('keimeno'),
    parousiasi: data.get('parousiasi')
  };

  fetch('/didaskwn/lista/api/saveGrades.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(r => r.json())
  .then(res => {
    if (res.error === 'already_graded') {
      alert('Έχεις ήδη καταχωρήσει βαθμό.');
      return;
    }
    if (res.error) {
      alert('Σφάλμα αποθήκευσης.');
      return;
    }

    alert('✅ Οι βαθμοί αποθηκεύτηκαν.');
    loadDetails();
  });
}



function loadAllGrades() {
  fetch(`/didaskwn/lista/api/getGrades.php?diplwmatikh_id=${window.DIPLO_ID}`)
    .then(r => r.json())
    .then(data => {
      const box = document.getElementById('all-grades');
      if (!box) return;

      if (!data.grades || data.grades.length === 0) {
        box.innerHTML = `
          <p class="muted">Δεν έχουν καταχωρηθεί βαθμοί.</p>
        `;
        return;
      }

      box.innerHTML = `
        <h3>📊 Καταχωρημένοι Βαθμοί</h3>
        <table class="grades-table">
          <thead>
            <tr>
              <th>Διδάσκων</th>
              <th>Ποιότητα</th>
              <th>Χρόνος</th>
              <th>Κείμενο</th>
              <th>Παρουσίαση</th>
              <th>Τελικός</th>
            </tr>
          </thead>
          <tbody>
            ${data.grades.map(g => `
              <tr class="${g.kathigitis_id === data.my_kathigitis_id ? 'my-grade' : ''}">
                <td>${g.kathigitis}</td>
                <td>${g.poiotita}</td>
                <td>${g.xronos}</td>
                <td>${g.keimeno}</td>
                <td>${g.parousiasi}</td>
                <td><strong>${g.telikos_vathmos}</strong></td>
              </tr>
            `).join('')}
          </tbody>
        </table>

        ${data.average !== null ? `
          <div class="final-grade">
            🎓 <strong>Μέσος Όρος:</strong> ${data.average}
          </div>
        ` : ''}
      `;
    });
}


