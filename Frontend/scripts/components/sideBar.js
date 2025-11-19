
// Wrap in Invek Function to prevent global variable conflicts between sidebar.js and header.js
(function(){
const params = new URLSearchParams(window.location.search);
const id = Number(params.get("id"));
const admin = params.get("admin");
const userLoalStorage = JSON.parse(localStorage.getItem("user") || null);

let navlinks;

if (admin) {
    navlinks = `
        <a href="home.html?id=${id}" class="nav-item">
            🤖 Ai Insights
        </a>
        <a href="progress.html?id=${id}" class="nav-item">
            📈 Progress
        </a>
        <a href="habits.html?id=${id}" class="nav-item">
            🔄 Habits
        </a>
        <a href="logs.html?id=${id}" class="nav-item">
            📋 Logs
        </a>
        <a href="users.html?id=${id}" class="nav-item">
            👥 Users 
        </a>
        `;
} else {
  navlinks = `
        <a href="home.html?id=${id}" class="nav-item">
            🤖 Ai Insights
        </a>
        <a href="progress.html?id=${id}" class="nav-item">
            📈 Progress
        </a>
        <a href="habits.html?id=${id}" class="nav-item">
            🔄 Habits
        </a>
        <a href="logs.html?id=${id}" class="nav-item">
            📋 Logs
        </a>
        <a href="updateprofile.html?id=${id}" class="nav-item">
            📋 Settings
        </a>
        `;
}

function sideBar() {
  const sidebar = document.getElementById("side-bar");
  sidebar.innerHTML = `<div class="sidebar-logo">
            <div class="logo">
                <img src="../assets/images/logo.png" />
            </div>
            <span class="logo-text">Habit Flow</span>
        </div>
        ${navlinks}
        `;
}

sideBar();
})()