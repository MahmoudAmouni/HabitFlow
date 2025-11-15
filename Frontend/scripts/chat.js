const DUMMY_DATA = [
  {
    type: "ai",
    text: 'Hi there! I\'m your habit coach. What\'s one thing you\'d like to track today? Just type it in — "walked 25 min", "drank 2 coffees", or "slept at 01:30". I\'ll turn it into clean data for you.',
  },
  {
    type: "user",
    text: "Walked 30 minutes, drank 1 coffee, slept at 11:30 PM",
  },
  {
    type: "ai",
    text: "Great job! You're building consistency. Your sleep is improving — last week you were sleeping at 1:15 AM. Keep going!",
  },
  { type: "user", text: "I want to drink more water" },
  {
    type: "ai",
    text: "Awesome goal! Try setting a reminder for 3 glasses of water by noon. I'll nudge you if you miss it. Small wins build big habits.",
  },
];

function createmsg(text, type) {
  const msgDiv = document.createElement("div");
  msgDiv.classList.add("chat-msg");
  msgDiv.classList.add(type === "user" ? "user-input" : "ai-response");
  msgDiv.textContent = text;
  return msgDiv;
}

function rendermsgs() {
  const chatContainer = document.getElementById("chat-container");
  chatContainer.innerHTML = ""; 

  DUMMY_DATA.map((msg) => {
    const msgElement = createmsg(msg.text, msg.type);
    chatContainer.appendChild(msgElement);
  });

  chatContainer.scrollTop = chatContainer.scrollHeight;
}

rendermsgs();

document.getElementById("send-btn").addEventListener("click", function () {
  const inputField = document.getElementById("user-input");
  const msg = inputField.value.trim();

  if (msg) {
    DUMMY_DATA.push({ type: "user", text: msg });
    rendermsgs();
    inputField.value = "";
    setTimeout(() => {
      DUMMY_DATA.push({
        type: "ai",
        text: "Interesting! What else did you notice today?",
      });
      rendermsgs();
    }, 1000);
  }
});

document.getElementById("user-input").addEventListener("keydown", function (e) {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();
    document.getElementById("send-btn").click();
  }
});
