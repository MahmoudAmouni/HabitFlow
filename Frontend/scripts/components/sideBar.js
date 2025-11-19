// Wrap in Invek Function to prevent global variable conflicts between sidebar.js and header.js
(function () {
  const params = new URLSearchParams(window.location.search);
  const id = Number(params.get("id"));
  const admin = params.get("admin");
  const userId = params.get("userId"); // Get userId from URL params
  const userLoalStorage = JSON.parse(localStorage.getItem("user") || null);

  let navlinks;

  if (userLoalStorage.role == 'admin') {
    navlinks = `
        <a href="home.html?id=${id}${
      userId ? "&userId=" + userId : ""
    }" class="nav-item">
            <img src="../assets/images/bot.png" width="20" height="20"/> Ai Insights
        </a>
        <a href="progress.html?id=${id}${
      userId ? "&userId=" + userId : ""
    }" class="nav-item">
            <img src="../assets/images/progress.png" width="20" height="20"/> Progress
        </a>
        <a href="habits.html?id=${id}${
      userId ? "&userId=" + userId : ""
    }" class="nav-item">
            <img src="../assets/images/repeat.png" width="20" height="20"/> Habits
        </a>
        <a href="logs.html?id=${id}${
      userId ? "&userId=" + userId : ""
    }" class="nav-item">
            <img src="../assets/images/paper.png" width="20" height="20"/> Logs
        </a>
        <a href="users.html?id=${id}${
      userId ? "&userId=" + userId : ""
    }" class="nav-item">
           <img src="../assets/images/users.png" width="20" height="20"/>  Users 
        </a>
        `;
  } else {
    navlinks = `
        <a href="home.html?id=${id}" class="nav-item">
            <img src="../assets/images/bot.png" width="20" height="20"/> Ai Insights
        </a>
        <a href="progress.html?id=${id}" class="nav-item">
            <img src="../assets/images/progress.png" width="20" height="20"/> Progress
        </a>
        <a href="habits.html?id=${id}" class="nav-item">
            <img src="../assets/images/repeat.png" width="20" height="20"/> Habits
        </a>
        <a href="logs.html?id=${id}" class="nav-item">
            <img src="../assets/images/paper.png" width="20" height="20"/> Logs
        </a>
        <a href="updateprofile.html?id=${id}" class="nav-item">
            <img src="../assets/images/settings.png" width="20" height="20"/> Settings
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
})();
