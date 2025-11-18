import { getAllHabits } from "../Apis/habits.js";
import { getAllLogs } from "../Apis/logs.js";
import { noHabits } from "../components/noHabits.js";

let allHabits;
let allLogs;
let chartInstance; 

async function initChart() {
  try {
    allHabits = await getAllHabits();
    allLogs = await getAllLogs();
    populateDropdown();
    createInitialChart();
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}

function populateDropdown() {
  const select = document.getElementById("select-habit-chart");
  select.innerHTML = "";

  allHabits.forEach((habit) => {
    const option = document.createElement("option");
    option.value = habit.id;
    option.textContent = habit.name;
    select.appendChild(option);
  });

  if (allHabits.length > 0) {
    select.value = allHabits[0].id;
  }

  select.addEventListener("change", function () {
    console.log(this.value);
    updateChart(Number(this.value));
  });
}

function createInitialChart() {
  const defaultLabels = generateLast7Days();
  createChart("chart", [0, 0, 0, 0, 0, 0, 0], "Select a habit", defaultLabels);

  const select = document.getElementById("select-habit-chart");
  if (select && select.value) {
    setTimeout(() => {
      updateChart(Number(select.value));
    }, 0);
  }
}

function updateChart(habitId) {
  const selectedHabit = allHabits.find((habit) => habit.id === habitId);
  if (!selectedHabit) return;

  const habitLogs = allLogs.filter((log) => log.habit_id === habitId);
  const { data, labels } = generateDataFromLogs(habitLogs);

  updateChartData(data, selectedHabit.name, labels);
}

function generateLast7Days() {
  const dates = [];
  const today = new Date();
  for (let i = 6; i >= 0; i--) {
    const date = new Date(today);
    date.setDate(today.getDate() - i);
    dates.push(
      date.toLocaleDateString("en-US", { month: "short", day: "numeric" })
    );
  }
  return dates;
}

function generateDataFromLogs(habitLogs) {
  const last7Days = [];
  const today = new Date();
  for (let i = 6; i >= 0; i--) {
    const date = new Date(today);
    date.setDate(today.getDate() - i);
    const dateStr = date.toISOString().split("T")[0]; 
    last7Days.push({
      dateStr,
      displayDate: date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
      }),
    });
  }

  const logMap = new Map();
  habitLogs.forEach((log) => {
    logMap.set(log.logged_at, parseInt(log.value) || 0);
  });

  const data = last7Days.map((dayInfo) => {
    return logMap.has(dayInfo.dateStr) ? logMap.get(dayInfo.dateStr) : 0;
  });

  const labels = last7Days.map((dayInfo) => dayInfo.displayDate);

  return { data, labels };
}

function createChart(containerId, data, title, labels) {
  const container = document.getElementById(containerId);
  container.innerHTML = "";

  const canvas = document.createElement("canvas");
  canvas.width = 800;
  canvas.height = 500;
  canvas.style.width = "100%";
  canvas.style.height = "100%";
  container.appendChild(canvas);

  const maxDataValue = Math.max(...data, 0);
  let suggestedMax;

  if (maxDataValue <= 5) suggestedMax = 10;
  else if (maxDataValue <= 20) suggestedMax = 25;
  else if (maxDataValue <= 1000)
    suggestedMax = Math.ceil(maxDataValue / 100) * 100 + 100;
  else suggestedMax = Math.ceil(maxDataValue / 1000) * 1000 + 1000;

  const config = {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: title,
          data: data,
          backgroundColor: "rgba(97, 160, 255, 0.7)",
          borderColor: "rgb(97, 160, 255)",
          borderWidth: 1,
          borderRadius: 6,
          borderSkipped: false,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: "rgba(26, 23, 41, 0.9)",
          titleColor: "#fff",
          bodyColor: "#fff",
          borderColor: "#61a0ff",
          borderWidth: 1,
          padding: 10,
          callbacks: {
            label: (context) => `${context.dataset.label}: ${context.parsed.y}`,
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          suggestedMax: suggestedMax,
          grid: { color: "rgba(255, 255, 255, 0.1)" },
          ticks: {
            color: "#fff",
            stepSize: Math.max(1, Math.ceil(suggestedMax / 5)),
          },
        },
        x: {
          grid: { display: false },
          ticks: { color: "#fff" },
        },
      },
      animation: { duration: 800, easing: "easeOutQuart" }, 
      transitions: {
        active: {
          animation: {
            duration: 800,
            easing: "easeOutQuart",
          },
        },
      },
    },
  };

  chartInstance = new Chart(canvas, config);
}

function updateChartData(newData, newTitle, newLabels) {
  if (!chartInstance) return;

  chartInstance.data.datasets[0].data = newData;
  chartInstance.data.datasets[0].label = newTitle;
  chartInstance.data.labels = newLabels;

  const maxDataValue = Math.max(...newData, 0);
  let suggestedMax;
  if (maxDataValue <= 5) suggestedMax = 10;
  else if (maxDataValue <= 20) suggestedMax = 25;
  else if (maxDataValue <= 1000)
    suggestedMax = Math.ceil(maxDataValue / 100) * 100 + 100;
  else suggestedMax = Math.ceil(maxDataValue / 1000) * 1000 + 1000;

  chartInstance.options.scales.y.suggestedMax = suggestedMax;

  chartInstance.update("active"); 
}

window.addEventListener("DOMContentLoaded", initChart);
