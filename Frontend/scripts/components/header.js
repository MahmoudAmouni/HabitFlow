// Initialize header based on screen width
const params = new URLSearchParams(window.location.search);
const id = Number(params.get("id"));
const admin = params.get("admin");
const userId = params.get("userId");
const user = JSON.parse(localStorage.getItem("user") || "null");

let navlinks;

if (admin) {
  navlinks = `
        <a href="home.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="sidebar-link">
            <img src="../assets/images/bot.png" width="20" height="20"/> Ai Insights
        </a>
        <a href="progress.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="sidebar-link">
            <img src="../assets/images/progress.png" width="20" height="20"/> Progress
        </a>
        <a href="habits.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="sidebar-link">
            <img src="../assets/images/repeat.png" width="20" height="20"/> Habits
        </a>
        <a href="logs.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="sidebar-link">
            <img src="../assets/images/paper.png" width="20" height="20"/> Logs
        </a>
        <a href="users.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="sidebar-link">
            👥 Users 
        </a>
        `;
} else {
  navlinks = `
        <a href="home.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="sidebar-link">
            <img src="../assets/images/bot.png" width="20" height="20"/> Ai Insights
        </a>
        <a href="progress.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="sidebar-link">
            <img src="../assets/images/progress.png" width="20" height="20"/> Progress
        </a>
        <a href="habits.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="sidebar-link">
            <img src="../assets/images/repeat.png" width="20" height="20"/> Habits
        </a>
        <a href="logs.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="sidebar-link">
            <img src="../assets/images/paper.png" width="20" height="20"/> Logs
        </a>
         <a href="updateprofile.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="sidebar-link">
            <img src="../assets/images/settings.png" width="20" height="20"/> Settings
        </a>
        `;
}

function initializeHeader() {
  const header = document.getElementById("header");
  const screenWidth = window.innerWidth;

  if (screenWidth < 768) {
    headerResponsive();
  } else {
    headerSimple();
  }
}

function headerSimple() {
  const header = document.getElementById("header");
  header.innerHTML = `
  <div><a href="updateprofile.html?id=${id}${
    userId ? "&userId=" + userId : ""
  }" class="header-title">Welcome Back ${user.name.toUpperCase()} ! </a></div>
    <button class="logout-btn" id="logout-btn">Log Out</button>
  `;
}

function headerResponsive() {
  const header = document.getElementById("header");
  header.innerHTML = `
    <div class="mobile-container">
    <div id="myLinks" class="sidebar-nav">
    <div class="sidebar-logo">
            <div class="logo">
                <img src="../assets/images/logo.png" />
            </div>
            <span class="logo-text">Habit Flow</span>
      </div>
        ${navlinks}
      </div>
      <div class="header-content">
        <div class="menu-toggle" id="menuToggle">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 12H21M3 6H21M3 18H21" stroke="white" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </div>
      </div>
      <button class="logout-btn" id="logout-btn">Log Out</button>
    </div>
  `;
}

window.addEventListener("resize", initializeHeader);

document.addEventListener("DOMContentLoaded", () => {
  initializeHeader();

  const logoutBtn = document.getElementById("logout-btn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      localStorage.removeItem("user");
      window.location.href = `http://localhost/HabitFlow/index.html`;
    });
  }

  const menuToggle = document.getElementById("menuToggle");
  const myLinks = document.getElementById("myLinks");
  if (menuToggle && myLinks) {
    menuToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      if (myLinks.classList.contains("active")) {
        myLinks.classList.remove("active");
      } else {
        myLinks.classList.add("active");
      }
    });

    document.addEventListener("click", (e) => {
      if (
        myLinks.classList.contains("active") &&
        !myLinks.contains(e.target) &&
        e.target !== menuToggle
      ) {
        myLinks.classList.remove("active");
      }
    });
  }
});
