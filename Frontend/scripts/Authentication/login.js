import { getUserByEmail, createUser } from "../Apis/users.js";

//Login
const loginBtn = document.getElementById("login-btn");
loginBtn.addEventListener("click", async () => {
  const email = document.getElementById("email-login").value.trim();
  const password = document.getElementById("password-login").value.trim();

  document.getElementById("email-error").textContent = "";
  document.getElementById("password-error").textContent = "";

  const data = await getUserByEmail(email, password);
  const user = data.data;
  console.log(user)

  if (data.status === 404) {
    document.getElementById("email-error").textContent =
      "Invalid email or password";
    document.getElementById("password-error").textContent =
      "Invalid email or password";
    return;
  }

  console.log(user);
  localStorage.setItem("user", JSON.stringify(user));
  const id = user.id;

   window.location.href = `http://localhost/HabitFlow/Frontend/pages/home.html?id=${id}`

});
