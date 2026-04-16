/* ======================================================
   ΑΡΧΙΚΟΠΟΙΗΣΗ
   ====================================================== */
document.addEventListener("DOMContentLoaded", () => {
    loadTopics();

    document
        .getElementById("createForm")
        .addEventListener("submit", createTopic);
});

/* ======================================================
   ΦΟΡΤΩΣΗ ΘΕΜΑΤΩΝ
   ====================================================== */
function loadTopics() {
    fetch("get_themata.php")
        .then(r => r.json())
        .then(d => {
            if (d.status !== "success") {
                alert("Σφάλμα φόρτωσης θεμάτων");
                return;
            }
            renderTopics(d.topics);
        })
        .catch(() => alert("Σφάλμα δικτύου"));
}

/* ======================================================
   RENDER ΘΕΜΑΤΩΝ
   ====================================================== */
function renderTopics(topics) {
    const list = document.getElementById("topicsList");
    list.innerHTML = "";

    if (!topics.length) {
        list.innerHTML = "<p>Δεν υπάρχουν θέματα.</p>";
        return;
    }

    topics.forEach(t => {
        const div = document.createElement("div");
        div.className = "topic-item";

        /* ---------- Βασικά ---------- */
        const title = document.createElement("h3");
        title.textContent = t.thema;

        const desc = document.createElement("p");
        desc.textContent = t.perigrafh;

        div.appendChild(title);
        div.appendChild(desc);

        /* ---------- Actions ---------- */
        const actions = document.createElement("div");
        actions.className = "actions";

        const editBtn = document.createElement("button");
        editBtn.textContent = "Τροποποίηση";
        editBtn.className = "edit-btn";

        const delBtn = document.createElement("button");
        delBtn.textContent = "Διαγραφή";
        delBtn.className = "delete-btn";

        actions.appendChild(editBtn);
        actions.appendChild(delBtn);
        div.appendChild(actions);

        /* ---------- Edit Form ---------- */
        const form = document.createElement("form");
        form.className = "edit-form";
        form.style.display = "none";

        const inputTitle = document.createElement("input");
inputTitle.type = "text";
inputTitle.name = "title";
inputTitle.classList.add("thema-input"); // 🔥 ΚΛΑΣΗ
inputTitle.required = true;
inputTitle.value = t.thema;


        const textarea = document.createElement("textarea");
        textarea.name = "description";
        textarea.required = true;
        textarea.value = t.perigrafh; // 🔥 ΤΟ ΚΡΙΣΙΜΟ

        const fileInput = document.createElement("input");
        fileInput.type = "file";
        fileInput.name = "file";
        fileInput.accept = "application/pdf";

        const hiddenFile = document.createElement("input");
        hiddenFile.type = "hidden";
        hiddenFile.name = "existing_file";
        hiddenFile.value = t.link || "";

        const saveBtn = document.createElement("button");
        saveBtn.type = "submit";
        saveBtn.textContent = "Αποθήκευση";

        const cancelBtn = document.createElement("button");
        cancelBtn.type = "button";
        cancelBtn.textContent = "Ακύρωση";

        form.appendChild(inputTitle);
        form.appendChild(textarea);
        form.appendChild(fileInput);
        form.appendChild(hiddenFile);
        const actionsWrap = document.createElement("div");
actionsWrap.className = "edit-form-actions";

actionsWrap.appendChild(saveBtn);
actionsWrap.appendChild(cancelBtn);

form.appendChild(actionsWrap);


        div.appendChild(form);
        list.appendChild(div);

        /* ---------- Events ---------- */
        editBtn.onclick = () => {
            form.style.display = "block";
        };

        cancelBtn.onclick = () => {
            form.style.display = "none";
        };

        form.onsubmit = e => {
            e.preventDefault();
            updateTopic(t.diplwmatikh_id, form);
        };

        delBtn.onclick = () => {
            deleteTopic(t.diplwmatikh_id);
        };
    });
}

/* ======================================================
   CREATE
   ====================================================== */
function createTopic(e) {
    e.preventDefault();

    const fd = new FormData(e.target);

    fetch("dimiourgia_themata.php", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(d => {
        if (d.status !== "success") {
            alert("Σφάλμα καταχώρησης");
            return;
        }
        e.target.reset();
        loadTopics();
    })
    .catch(() => alert("Σφάλμα δικτύου"));
}

/* ======================================================
   UPDATE
   ====================================================== */
function updateTopic(topicId, form) {
    const fd = new FormData(form);
    fd.append("topic_id", topicId);

    fetch("enimerosi_themata.php", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(d => {
        if (d.status !== "success") {
            alert(d.message || "Σφάλμα ενημέρωσης");
            return;
        }
        loadTopics();
    })
    .catch(() => alert("Σφάλμα δικτύου"));
}

/* ======================================================
   DELETE
   ====================================================== */
function deleteTopic(topicId) {
    if (!confirm("Θέλεις σίγουρα να διαγράψεις το θέμα;")) return;

    fetch("diagrafi_themata.php", {
        method: "POST",
        body: new URLSearchParams({ topic_id: topicId })
    })
    .then(r => r.json())
    .then(d => {
        if (d.status !== "success") {
            alert("Σφάλμα διαγραφής");
            return;
        }
        loadTopics();
    })
    .catch(() => alert("Σφάλμα δικτύου"));
}



