function header(){
    const header = document.getElementById("header");
    header.innerHTML = `<div class="header-links">
                <a href="#" class="header-link">Account</a>
            </div>
            <button class="logout-btn" id="logout-btn">Log Out</button>`;
}

header()

const logoutBtn =document.getElementById("logout-btn");

logoutBtn.addEventListener('click',()=>{
    localStorage.removeItem('user')
    window.location.href = `http://localhost/HabitFlow/index.html`;
})