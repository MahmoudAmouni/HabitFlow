function sideBar() {
  const sidebar = document.getElementById("side-bar");
  sidebar.innerHTML = `<div class="sidebar-logo">
            <div class="logo">
                <img src="../assets/images/logo.png" />
            </div>
            <span class="logo-text">Habit Flow</span>
        </div>

        <div class="nav-item active">
            📊 Dashboard
        </div>
        <div class="nav-item">
            📈 Progress
        </div>
        <div class="nav-item">
            🎯 Goals
        </div>
        <div class="nav-item">
            🔔 Reminders
        </div>
        <div class="nav-item">
            ⚙️ Settings
        </div>`;
}

sideBar();
