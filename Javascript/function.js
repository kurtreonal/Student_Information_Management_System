function toggleNav() {
    const sidebar = document.getElementById("mySidebar");
    const main = document.getElementById("main");
    sidebar.classList.toggle("closed");
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle("open");
    }
}