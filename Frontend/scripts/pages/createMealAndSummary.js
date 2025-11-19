const cron = require("node-cron");
const { createAiMeal, createAiSummary } = require("../Apis/aiResponse.js");

cron.schedule("0 13 * * *", async () => {
  try {
    const suggestion = await createAiMeal();
    console.log("Meal suggestion created:", suggestion);
  } catch (error) {
    console.error("Error creating meal suggestion:", error);
  }
});

cron.schedule("0 0 * * *", async () => {
  try {
    const summary = await createAiSummary("daily");
    console.log("Daily summary created:", summary);
  } catch (error) {
    console.error("Error creating daily summary:", error);
  }
});

cron.schedule("0 8 * * 0", async () => {
  try {
    const summary = await createAiSummary("weekly");
    console.log("Weekly summary created:", summary);
  } catch (error) {
    console.error("Error creating weekly summary:", error);
  }
});

cron.schedule("0 9 1 * *", async () => {
  try {
    const summary = await createAiSummary("monthly");
    console.log("Monthly summary created:", summary);
  } catch (error) {
    console.error("Error creating monthly summary:", error);
  }
});
