document.addEventListener("DOMContentLoaded", function () {
    const topbar = document.querySelector(".fi-topbar");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 20) {
            topbar.classList.add("scrolled");
        } else {
            topbar.classList.remove("scrolled");
        }
    });
});
