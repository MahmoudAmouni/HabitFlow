import { getUserById } from "../Apis/users.js";

const params = new URLSearchParams(window.location.search);
const id = Number(params.get("id"));
const userLoalStorage = JSON.parse(localStorage.getItem("user"));

async function checkUserValidity() {
  const user = await getUserById(id);
  if (!user || user.status === 404 ) {
    localStorage.removeItem("user");
    window.location.href = `http://localhost/HabitFlow/index.html`;
    return;
  }
  if (
    !userLoalStorage ||
    id !== userLoalStorage.id 
  ) {
    localStorage.removeItem("user");
    window.location.href = `http://localhost/HabitFlow/index.html`;
  }
}

if(userLoalStorage.role!=='admin'){
  checkUserValidity()
}
