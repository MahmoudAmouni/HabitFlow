import { createUser } from "../Apis/users.js";

const submitBtn = document.getElementById("submit-btn");

submitBtn.addEventListener("click", async () => {
  const username = document.getElementById("username").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();
  const height = document.getElementById("height").value.trim();
  const weight = document.getElementById("weight").value.trim();
  const gender = document.querySelector('input[name="gender"]:checked')?.value;

  document.getElementById("username-error").textContent = "";
  document.getElementById("email-error").textContent = "";
  document.getElementById("password-error").textContent = "";
  document.getElementById("height-error").textContent = "";
  document.getElementById("weight-error").textContent = "";
  document.getElementById("gender-error").textContent = "";

  let hasErrors = false;

  if (!username) {
    document.getElementById("username-error").textContent =
      "Username is required";
    hasErrors = true;
  }

  if (!email) {
    document.getElementById("email-error").textContent = "Email is required";
    hasErrors = true;
  } else if (!/^\S+@\S+\.\S+$/.test(email)) {
    document.getElementById("email-error").textContent =
      "Please enter a valid email";
    hasErrors = true;
  }

  if (!password) {
    document.getElementById("password-error").textContent =
      "Password is required";
    hasErrors = true;
  } else if (password.length < 8) {
    document.getElementById("password-error").textContent =
      "Password must be at least 8 characters";
    hasErrors = true;
  }

  if (!height) {
    document.getElementById("height-error").textContent = "Height is required";
    hasErrors = true;
  } else if (isNaN(height) || height <= 0) {
    document.getElementById("height-error").textContent =
      "Please enter a valid height";
    hasErrors = true;
  }

  if (!weight) {
    document.getElementById("weight-error").textContent = "Weight is required";
    hasErrors = true;
  } else if (isNaN(weight) || weight <= 0) {
    document.getElementById("weight-error").textContent =
      "Please enter a valid weight";
    hasErrors = true;
  }

  if (!gender) {
    document.getElementById("gender-error").textContent =
      "Please select a gender";
    hasErrors = true;
  }

  if (hasErrors) return;

  const result = await createUser(
    username,
    email,
    password,
    height,
    weight,
    gender
  );

  if (result && result.status === 201) {
    window.location.href = "http://localhost/HabitFlow/login.html";
  } else if (result && result.status === 500) {
    document.getElementById("email-error").textContent = "Email already exists";
  }
});
