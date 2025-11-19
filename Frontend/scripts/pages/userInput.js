import { handleCreateLogsFromAiResponse } from "../Apis/aiResponse.js";
import { createLogFromAiResponse } from "../Apis/logs.js";

let aiResponse;
async function handleInput(text) {
  aiResponse = await handleCreateLogsFromAiResponse(text);
  console.log(aiResponse);
}

function showCardModal() {
  document.getElementById("cardModal").style.display = "flex";
}

function closeCardModal() {
  document.getElementById("cardModal").style.display = "none";
}

async function submitCard() {
  await createLogFromAiResponse();
  closeCardModal();
}

// Add event listeners for both close buttons
document.getElementById("close-btn").addEventListener("click", () => {
  closeCardModal();
});

document.getElementById("close-btn2").addEventListener("click", () => {
  closeCardModal();
});

// Add event listener for submit button
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
        return `<p class="user-input-logs">${item.habit_name.toUpperCase()}: ${item.value} ${item.unit}</p>`;
      })
      .join("");
  });
});
