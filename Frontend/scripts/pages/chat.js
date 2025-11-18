import { getAllAiMeals, getAllAiSummarys } from "../Apis/aiResponse.js";

let aiSummary;
let aiMeal;

async function renderResponsesandMeals() {
  try {
    // Fetch both API calls
    const [mealsResponse, summariesResponse] = await Promise.all([
      getAllAiMeals(),
      getAllAiSummarys(),
    ]);

    // Store the data (assuming they return arrays directly)
    aiMeal = mealsResponse;
    aiSummary = summariesResponse;

    renderCombinedData();
  } catch (error) {
    console.error("Error fetching AI ", error);
  }
}

function formatAndSortData() {
  const allItems = [];

  // Process meals data
  if (aiMeal && Array.isArray(aiMeal)) {
    aiMeal.map((item) => {
      const createdAt = new Date(item.created_at);
      console.log(createdAt)
        allItems.push({
          type: "meal",
          title: item.title,
          summary: item.summary,
          url: item.url,
          created_at: createdAt,
          suggestion: item.url,
        });
      
    });
  }
  // console.log(allItems);

  // Process summaries data
  if (aiSummary && Array.isArray(aiSummary)) {
    aiSummary.map((item) => {
     console.log(item)

      const createdAt = new Date(item.created_at).getTime();
        allItems.push({
          type: "summary",
          title: item.title,
          summary: item.summary,
          suggestion: item.suggestion,
          created_at: createdAt,
          url: item.suggestion,
        });
    });
  }

  // Sort by created_at timestamp (newest first)
  allItems.sort((a, b) => b.created_at - a.created_at);

  return allItems;
}

function createAiResponseElement(item) {
  const responseDiv = document.createElement("div");
  responseDiv.classList.add("chat-msg", "ai-response");

  let contentHTML = "";

  if (item.type === "meal") {
    contentHTML = `
      <div class="ai-meal">
        <h3>${item.title}</h3>
        <p>${item.summary}</p>
        <img src="${item.url}" alt="${item.title}" style="max-width: 200px; border-radius: 8px;">
      </div>
    `;
  } else {
    // summary
    contentHTML = `
      <div class="ai-summary">
        <h3>${item.title}</h3>
        <p>${item.summary}</p>
        <p><strong>Suggestion:</strong> ${item.suggestion}</p>
      </div>
    `;
  }

  // Add timestamp (convert back to readable format)
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

// Load data when the page loads
document.addEventListener("DOMContentLoaded", () => {
  renderResponsesandMeals();
});
