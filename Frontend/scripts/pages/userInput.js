import { handleCreateLogsFromAiResponse } from "../Apis/aiResponse.js";
import { createLogFromAiResponse } from "../Apis/logs.js";

const user = JSON.parse(localStorage.getItem("user") || "null");
let aiResponse;

async function handleInput(text) {
  aiResponse = await handleCreateLogsFromAiResponse(text);
}

function showCardModal() {
  document.getElementById("cardModal").style.display = "flex";
}

function closeCardModal() {
  document.getElementById("cardModal").style.display = "none";
}

async function submitCard() {
  if (!aiResponse) {
    console.error("No AI response data available");
    return;
  }

  const transformedData = aiResponse.map((item) => ({
    user_id: user.id,
    habit_id: item.habit_id,
    value: item.value,
  }));
console.log(transformedData)
  const data = await createLogFromAiResponse(transformedData);
  console.log(data);
  closeCardModal();
}

document.getElementById("close-btn").addEventListener("click", () => {
  closeCardModal();
});

document.getElementById("close-btn2").addEventListener("click", () => {
  closeCardModal();
});

document.getElementById("submit-btn").addEventListener("click", () => {
  submitCard();
});

document.addEventListener("DOMContentLoaded", () => {
  const addButton = document.getElementById("send-btn");

  addButton.addEventListener("click", async () => {
    const userInput = document.getElementById("user-input").value;
    await handleInput(userInput);
    showCardModal();

      document.getElementById("habit-data").innerHTML = aiResponse
        .map((item) => {
          return `<p class="user-input-logs">${item.habit_name.toUpperCase()}: ${
            item.value
          } ${item.unit}</p>`;
        })
        .join("");
  });
});
