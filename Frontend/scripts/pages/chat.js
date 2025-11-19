import { deleteAiMeal, deleteAiResponse, getAllAiMeals, getAllAiSummarys } from "../Apis/aiResponse.js";
const image = `https://lh3.googleusercontent.com/p/AF1QipORWkDpWO4eeNTIKVajwa9IkprBH82e44i1b0Ws=w115-h115-n-k-no`;
let aiSummary;
let aiMeal;

async function renderResponsesandMeals() {
  try {
    [aiMeal, aiSummary] = await Promise.all([
      getAllAiMeals(),
      getAllAiSummarys(),
    ]);
    console.log(aiSummary);

    renderCombinedData();
  } catch (error) {
    console.error("Error fetching AI ", error);
  }
}

function formatAndSortData() {
  const allItems = [];

  if (aiMeal) {
    aiMeal.map((item) => {
      const createdAt = new Date(item.created_at);
      allItems.push({
        id: item.id,
        type: "meal",
        title: item.title,
        summary: item.summary,
        url: item.url,
        created_at: createdAt,
        suggestion: item.url,
      });
    });
  }

  if (aiSummary) {
    aiSummary.map((item) => {
      const createdAt = new Date(item.created_at).getTime();
      console.log(item.id);
      allItems.push({
        id: item.id,
        type: "summary",
        title: item.title,
        summary: item.summary,
        suggestion: item.suggestion,
        created_at: createdAt,
        url: item.suggestion,
      });
    });
  }

  allItems.sort((a, b) => a.created_at - b.created_at);

  return allItems;
}

function createAiResponseElement(item) {
  const responseDiv = document.createElement("div");
  responseDiv.classList.add("chat-msg", "ai-response");

  let contentHTML = "";

  if (item.type === "meal") {
    contentHTML = `
      <div class="ai-meal">
      <div class='ai-text'>
      <h3>${item.title}</h3>
      <div class="ai-card-content">
      
      <p>${item.summary}</p>
      <img src="${image}" alt="${item.title}" style="max-width: 200px; border-radius: 8px;">
      </div>
      </div>
      <div class="item-actions">
                <button class="item-btn delete-btn" data-id="${item.id}" data-type="${item.type}">🗑️</button>
            </div>
      </div>
    `;
  } else {
    contentHTML = `
      <div class="ai-summary">
      <div class='ai-text'>
        <h3>${item.title}</h3>
        <p>${item.summary}</p>
        <p><strong>Suggestion:</strong> ${item.suggestion}</p>
      </div>
        <div class="item-actions">
                <button class="item-btn delete-btn" data-id="${item.id}" data-type="${item.type}">🗑️</button>
            </div>
      </div>
    `;
  }

  const date = new Date(item.created_at);
  const options = {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  const timestamp = date.toLocaleDateString(undefined, options);
  contentHTML += `<div class="timestamp">${timestamp}</div>`;

  responseDiv.innerHTML = contentHTML;


  const deleteBtn = responseDiv.querySelector(".delete-btn");
  deleteBtn.addEventListener("click", (e) => {
    e.stopPropagation(); 
    handleDelete(item.id, item.type);
  });

  return responseDiv;
}

function renderCombinedData() {
  const allItems = formatAndSortData();

  const chatContainer = document.getElementById("chat-container");
  chatContainer.innerHTML = "";

  if (allItems.length === 0) {
    const noDataDiv = document.createElement("div");
    noDataDiv.classList.add("chat-msg", "ai-response");
    noDataDiv.textContent = "No AI responses available yet.";
    chatContainer.appendChild(noDataDiv);
    return;
  }

  allItems.map((item) => {
    const responseElement = createAiResponseElement(item);
    chatContainer.appendChild(responseElement);
  });

  chatContainer.scrollTop = chatContainer.scrollHeight;
}

async function handleDelete(id, type) {
  if(confirm('Are you sure ?')) type ==='meal' ? deleteAiMeal(id) : deleteAiResponse(id)
    renderResponsesandMeals();
}

document.addEventListener("DOMContentLoaded", () => {
  renderResponsesandMeals();
});
