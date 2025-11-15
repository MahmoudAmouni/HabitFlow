const user = JSON.parse(localStorage.getItem("user") || "null");

const params = new URLSearchParams(window.location.search);
const id = Number(params.get('id')); 

if(!user || !user.email || !user.height){
    window.location.href = `http://localhost/HabitFlow/index.html`;
}

if(id!==user.id){
    localStorage.removeItem("user")
    window.location.href = `http://localhost/HabitFlow/index.html`;
}