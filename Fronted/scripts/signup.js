import { createUser } from "./Apis.js";

//Sign Up
const submitBtn = document.getElementById("submit-btn");

submitBtn.addEventListener("click",async ()=>{
    const username = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const height = document.getElementById("height").value.trim();
    const weight = document.getElementById("weight").value.trim();
    const gender = document.querySelector(
      'input[name="gender"]:checked'
    )?.value;



    await createUser(username,email,password,height,weight,gender)
})





