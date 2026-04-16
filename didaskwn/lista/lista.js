document.addEventListener('DOMContentLoaded', () => {
  loadDiplwmatikes();

  document.getElementById('filters').addEventListener('submit', e => {
    e.preventDefault();
    loadDiplwmatikes();
  });
});

function loadDiplwmatikes() {
  const params = new URLSearchParams(
    new FormData(document.getElementById('filters'))
  );

  fetch('/didaskwn/lista/api/get_diplwmatikes.php?' + params.toString())
    .then(res => res.json())
    .then(data => renderList(data))
    .catch(() => {
      document.getElementById('diplwmatikes-list').innerHTML =
        '<p>Σφάλμα φόρτωσης.</p>';
    });
}

function renderList(list) {
  const c = document.getElementById('diplwmatikes-list');

  if (!list || !list.length) {
    c.innerHTML = '<p>Δεν βρέθηκαν διπλωματικές.</p>';
    return;
  }

  c.innerHTML = `
    <section class="diplwmatikes-list">
      <ul>
        ${list.map(d => `
          <li>
            <h3>${escapeHTML(d.thema)}</h3>
            <p><strong>Κατάσταση:</strong> ${escapeHTML(d.katastash)}</p>
            <p><strong>Φοιτητής:</strong> ${escapeHTML(d.foititis ?? '—')}</p>
            <p><strong>Ρόλος:</strong> ${escapeHTML(d.rolos)}</p>
            <a class="details-btn"
               href="details.php?diplwmatikh_id=${d.diplwmatikh_id}">
              Προβολή Λεπτομερειών
            </a>
          </li>
        `).join('')}
      </ul>
    </section>
  `;
}

function exportDiplwmatikes(format) {
  const params = new URLSearchParams(
    new FormData(document.getElementById('filters'))
  );

  fetch('/didaskwn/lista/api/csvdiplwmatikes.php?format=' + format + '&' + params.toString())

    .then(res => res.blob())
    .then(blob => {
      const a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = `diplwmatikes.${format}`;
      a.click();
    });
}

function escapeHTML(str) {
  return String(str).replace(/[&<>"']/g, m =>
    ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])
  );
}


