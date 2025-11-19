import {
  createHabit,
  deleteHabit,
  editHabit,
  getAllHabits,
} from "../Apis/habits.js";
import { showToast } from "../components/toast.js";
const params = new URLSearchParams(window.location.search);
if (params.get("userId")) {
  const button = document.getElementById("add-habit-btn");
  button.disabled = true;
  button.style.cursor = "not-allowed";
}

let habit_id = null;

async function renderHabits() {
  const habitsContainer = document.getElementById("habits-container");
  habitsContainer.innerHTML = "";
  const habits = await getAllHabits();

  if (habits.length === 0) {
    habitsContainer.innerHTML = `
      <div class="empty-habits">
        <p>No habits found. Add new habits to get started!</p>
      </div>
    `;
    return;
  }

  habits.map((habit) => {
    const habitItem = document.createElement("div");
    habitItem.className = "habit-item";
    habitItem.innerHTML = `
            <div class="habit-name">${habit.name}</div>
            <div class="habit-actions">
                <button class="habit-btn edit-btn" data-id="${habit.id}"><img src="../assets/images/edit.png" width="20" height="20"/></button>
                <button class="habit-btn delete-btn" data-id="${habit.id}"><img src="../assets/images/trash.png" width="20" height="20"/></button>
            </div>
        `;
    habitsContainer.appendChild(habitItem);
  });

  document.querySelectorAll(".edit-btn").forEach((button) => {
    button.addEventListener("click", handleEditClick);
    if (params.get("userId")) {
      button.disabled = true;
      button.style.cursor = "not-allowed";
    }
  });
  document.querySelectorAll(".delete-btn").forEach((button) => {
    button.addEventListener("click", handleDeleteClick);
    if (params.get("userId")) {
      button.disabled = true;
      button.style.cursor = "not-allowed";
    }
  });
}

function handleEditClick(e) {
  const habitId = e.target.dataset.id;
  document.getElementById("modal-overlay").classList.add("active");
  document.body.classList.add("modal-open");
  document.getElementById("habit-name").value = "";
  document.getElementById("habit-unit").value = "";
  document.getElementById("habit-target").value = "";
  habit_id = habitId;
  document.getElementById("save-habit-btn").textContent = "Update Habit";
}

async function handleDeleteClick(e) {
  const habitId = e.target.dataset.id;
  if (confirm("Are you sure u want to delete ?")) {
    const data = await deleteHabit(habitId);
    renderHabits();
    showToast("Habit deleted successfully");
  }
}

document.getElementById("add-habit-btn").addEventListener("click", function () {
  document.getElementById("modal-overlay").classList.add("active");
  document.body.classList.add("modal-open");
  document.getElementById("habit-name").value = "";
  document.getElementById("habit-unit").value = "";
  document.getElementById("habit-target").value = "";
  habit_id = null;
  document.getElementById("save-habit-btn").textContent = "Add Habit";
});

function closeModal() {
  document.getElementById("modal-overlay").classList.remove("active");
  document.body.classList.remove("modal-open");
  habit_id = null;
  document.getElementById("save-habit-btn").textContent = "Add Habit";
}

document
  .getElementById("cancel-habit-btn")
  .addEventListener("click", closeModal);

document
  .getElementById("save-habit-btn")
  .addEventListener("click", async function () {
    const name = document.getElementById("habit-name").value.trim();
    const unit = document.getElementById("habit-unit").value.trim();
    const target = document.getElementById("habit-target").value;

    if (!name || !unit || isNaN(target)) {
      alert("Please fill all fields correctly");
      return;
    }

    let data;
    if (habit_id) {
      data = await editHabit(name, unit, target, habit_id);
      showToast("Habit updated successfully");
    } else {
      data = await createHabit(name, unit, target);
      showToast("Habit created successfully");
    }

    if (data.status === 500) {
      alert("Habit already exists");
      return;
    }

    closeModal();
    await renderHabits();
  });

document
  .getElementById("modal-overlay")
  .addEventListener("click", function (e) {
    if (e.target === document.getElementById("modal-overlay")) {
      closeModal();
    }
  });

document.addEventListener("keydown", function (e) {
  if (
    e.key === "Escape" &&
    document.getElementById("modal-overlay").classList.contains("active")
  ) {
    closeModal();
  }
});

window.addEventListener("DOMContentLoaded", async () => await renderHabits());
