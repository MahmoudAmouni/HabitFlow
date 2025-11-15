import { getUserByEmail } from "../Apis.js";

//Login
const loginBtn = document.getElementById("login-btn");
loginBtn.addEventListener("click", async () => {
  const email = document.getElementById("email-login").value.trim();
  const password = document.getElementById("password-login").value.trim();

  const user = await getUserByEmail(email, password);
  console.log(user)
  localStorage.setItem("user", JSON.stringify(user));
  const id = user.id;
  window.open(`http://localhost/HabitFlow/Frontend/pages/home.html?id=${id}`, "_blank");
  console.log("http://localhost/HabitFlow/Frontend/pages/login.html");
});
