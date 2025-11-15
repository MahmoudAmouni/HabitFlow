import { getUserByEmail } from "./Apis.js";

//Login
const loginBtn = document.getElementById("login-btn");
loginBtn.addEventListener("click", async () => {
  const email = document.getElementById("email-login").value.trim();
  const password = document.getElementById("password-login").value.trim();

  await getUserByEmail(email, password);
});
