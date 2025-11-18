import { getAllHabits } from "../Apis/habits.js";
import { getAllLogs, deleteLog, createLog } from "../Apis/logs.js";
const params = new URLSearchParams(window.location.search);
let habitsData = []; 
if (params.get("userId")) {
  const habitSelect = document.getElementById("habit-select");
  const dateSelect = document.getElementById("date-select");
  const logSInput = document.getElementById("log-input");
  habitSelect.disabled = true;
  dateSelect.disabled = true;
  logSInput.disabled = true;
  habitSelect.style.cursor = "not-allowed";
  dateSelect.style.cursor = "not-allowed";
  logSInput.style.cursor = "not-allowed";
}

async function renderLogs() {
  const logsContainer = document.getElementById("logs-container");
  logsContainer.innerHTML = "";

  const [logsData, fetchedHabitsData] = await Promise.all([
    getAllLogs(),
    getAllHabits(),
  ]);

  habitsData = fetchedHabitsData; 
  

  const habitSelect = document.getElementById("habit-select");
  habitSelect.innerHTML = "";

  habitsData.map((habit) => {
    const option = document.createElement("option");
    option.value = habit.id;
    option.textContent = habit.name;
    habitSelect.appendChild(option);
  });

  // dates.map((date) => {
  //   const option = document.createElement("option");
  //   option.value = date;
  //   option.textContent = new Date(date).toLocaleDateString();
  //   dateSelect.appendChild(option);
  // });

  console.log(logsData);
  console.log(habitsData);

  logsData.map((log) => {
    const habit = habitsData.find((habit) => habit.id == log.habit_id);
    const habitName = habit ? habit.name : "Unknown Habit";
    const habitUnit = habit ? habit.unit : "Unknown unit";

    const logItem = document.createElement("div");
    logItem.className = "log-item";
    logItem.innerHTML = `
            <div class="log-text">${log.value}-${habitUnit} <span style="color: rgba(255,255,255,0.5); font-size: 0.85rem;">(${habitName} • ${log.logged_at})</span></div>
            <div class="log-actions">
                <button class="log-btn delete-btn" data-id="${log.id}">🗑️</button>
            </div>
        `;
    logsContainer.appendChild(logItem);
    
    
  });

    document.querySelectorAll(".delete-btn").forEach((button)=>{
        button.addEventListener('click',handleDeleteLog)
        if (params.get("userId")) {
          button.disabled = true;
          button.style.cursor = "not-allowed";
        }
    })
    
  };


async function handleDeleteLog(e){
    const LogId = e.target.dataset.id;
    if(confirm("Are you sure you want to delete ?")){
        await deleteLog(LogId)
        renderLogs()
    }
}

function updateUnitBox() {
  const habitSelect = document.getElementById("habit-select");
  const unitBox = document.getElementById("unit-box");
  const selectedHabitName = habitSelect.value
    ? habitsData.find((habit) => habit.id == habitSelect.value)?.name
    : null;

  if (selectedHabitName) {
    const selectedHabit = habitsData.find(
      (habit) => habit.name === selectedHabitName
    );
    console.log(selectedHabit)
    unitBox.textContent = selectedHabit ? selectedHabit.unit : "";
  } else {
    unitBox.textContent = "";
  }
}

document
  .getElementById("habit-select")
  .addEventListener("change", updateUnitBox);

document
  .getElementById("add-log-btn")
  .addEventListener("click", async function () {
    const habitSelect = document.getElementById("habit-select");
    const selectedHabitId = parseInt(habitSelect.value);
    // const date = document.getElementById("date-select").value;
    const value = document.getElementById("log-input").value.trim();
    
    if (!value) {
        alert("Please enter a log entry");
        return;
    }
    await createLog(value,selectedHabitId)

    document.getElementById("log-input").value = "";
    await renderLogs();
  });

window.addEventListener("DOMContentLoaded", async function () {
  await renderLogs();
  document.getElementById("unit-box").innerHTML = habitsData[0].unit;
});
