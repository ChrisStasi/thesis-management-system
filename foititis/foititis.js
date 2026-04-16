document.addEventListener("DOMContentLoaded", function () {
    const userFullName = document.getElementById("userFullName");
    
    if (userFullName.textContent === "") {
        userFullName.textContent = "Foititis";
    }
});
