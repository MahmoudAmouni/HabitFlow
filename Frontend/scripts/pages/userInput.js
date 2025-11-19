let aiResponse;
const dummyDATA = [
  {
    habit_name: "Running",
    value: 2,
    unit: "km",
  },
  {
    habit_name: "Running",
    value: 2,
    unit: "km",
  },

  {
    habit_name: "Running",
    value: 2,
    unit: "km",
  },

  {
    habit_name: "Running",
    value: 2,
    unit: "km",
  },
];


function showCardModal() {
  document.getElementById("cardModal").style.display = "flex";
}

function closeCardModal() {
  document.getElementById("cardModal").style.display = "none";
}

function submitCard() {
  const input = document.querySelector(".modal-input");
  console.log("Submitted:", input.value);

  closeCardModal();
}
document.getElementById("close-btn").addEventListener("click", () => {
  closeCardModal();
});

document.addEventListener("DOMContentLoaded", () => {
  const addButton = document.getElementById("send-btn");
  if (addButton) {
    addButton.addEventListener("click", () => {
      showCardModal();
      aiResponse = dummyDATA;
      document.getElementById("habit-data").innerHTML = dummyDATA
        .map((item) => {
          return `<p>${item.habit_name}: ${item.value} ${item.unit}</p>`;
        })
        .join("");
    });
  }

  const blueArrowButton = document.getElementById("blue-arrow-button");
  if (blueArrowButton) {
    blueArrowButton.addEventListener("click", showCardModal);
  }
});
