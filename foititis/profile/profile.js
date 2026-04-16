document.addEventListener("DOMContentLoaded", () => {
    loadProfile();
    document.getElementById("profileForm").addEventListener("submit", updateProfile);
});

function loadProfile() {
    fetch("get_profile.php")
        .then(r => r.json())
        .then(d => {
            if (d.status !== "success") return;

            const u = d.data;
            fullname.value  = u.Plhres_onoma;
            am.value        = u.am;
            email.value     = u.email;
            thlefwno.value  = u.thlefwno;
            stathero.value  = u.stathero ?? "";
            diefthinsi.value= u.diefthinsi ?? "";
        });
}

function updateProfile(e) {
    e.preventDefault();

    const fd = new FormData();
    fd.append("email", email.value);
    fd.append("thlefwno", thlefwno.value);
    fd.append("stathero", stathero.value);
    fd.append("diefthinsi", diefthinsi.value);

    fetch("enimerosi_profile.php", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(d => {
    const msg = document.getElementById("message");

    msg.className = d.status === "success"
        ? "success-message"
        : "error-message";

    msg.textContent = d.message;

    // ⏳ αυτόματη εξαφάνιση μετά από 3 δευτερόλεπτα
    setTimeout(() => {
        msg.textContent = "";
        msg.className = "";
    }, 3000);
});

}
