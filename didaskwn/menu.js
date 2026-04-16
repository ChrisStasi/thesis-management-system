document.addEventListener("DOMContentLoaded", function () {
    const menuIcon = document.getElementById("menu-icon");
    const menuOptions = document.getElementById("menu-options");

    // Εναλλαγή εμφάνισης/απόκρυψης του μενού
    menuIcon.addEventListener("click", function () {
        menuOptions.classList.toggle("active");
    });
});