import { getUserById } from "../Apis/users.js";

const params = new URLSearchParams(window.location.search);
const id = Number(params.get("id"));
const admin = params.get("admin")
const userLoalStorage = JSON.parse(localStorage.getItem("user") || null);


async function checkUserValidity() {
  const user = await getUserById(id);
  if (user.status === 404) {
    localStorage.removeItem("user");
    window.location.href = `http://localhost/HabitFlow/index.html`;
    return;
  }
  if (
    !userLoalStorage ||
    id !== userLoalStorage.id ||
    !userLoalStorage.email ||
    !userLoalStorage.height
  ) {
    localStorage.removeItem("user");
    window.location.href = `http://localhost/HabitFlow/index.html`;
  }
}
if(admin !== userLoalStorage.role){
  // checkUserValidity();
}
