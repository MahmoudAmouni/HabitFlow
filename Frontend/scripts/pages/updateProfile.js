import { getUserById, updateUser } from "../Apis/users.js";
  const emailRegex =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  

async function renderUserData(){
    const user = await getUserById()
    const username = document.getElementById("username");
    const email = document.getElementById("email");

    username.value = user.name
    email.value = user.email
}

document
  .getElementById("update-user-btn")
  .addEventListener("click", async () => {
    let isValid =true;
    const username = document.getElementById("username").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("new-password").value;
    const confirmPassword = document.getElementById("confirm-password").value;
    if (!username || username.trim() === "") {
      document.getElementById("username-error").textContent =
        "Username is required";
      isValid = false;
    }

    if (!email || !emailRegex.test(email)) {
      document.getElementById("email-error").textContent =
        "Please enter a valid email address";
      isValid = false;
    }

    if (!password || password.length < 8) {
      document.getElementById("password-error").textContent =
        "Password must be at least 8 characters long";
      isValid = false;
    }

    if (password !== confirmPassword) {
      document.getElementById("confirm-password-error").textContent =
        "Passwords do not match";
      isValid = false;
    }
    if (!isValid) return;
    console.log(username,email,password)
    const res =await updateUser(username, email, password);
    console.log(res)
    if(res.status ===500){
        document.getElementById("email-error").textContent =
          "Email already taken"
    }else{
      const user = await getUserById();
      localStorage.removeItem("user");
      localStorage.setItem("user", JSON.stringify(user));

    }
  });

window.addEventListener("DOMContentLoaded", async () => await renderUserData());