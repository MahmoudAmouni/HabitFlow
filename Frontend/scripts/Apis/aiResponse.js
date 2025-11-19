async function createAiSummary(type){
      const user = JSON.parse(localStorage.getItem("user") || "null");
    try {
        const res = await fetch(
          "http://localhost/HabitFlow/Backend/AiAnalyze",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              user_id: user.id,
              type
            }),
          }
        );
        const data = await res.json()
        return data.data
    } catch (error) {
        console.looh(error)
    }
}

async function createAiMeal() {
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/AiAnalyze", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        user_id: user.id,
        type:'meal',
      }),
    });
    const data = await res.json();
    return data.data
  } catch (error) {
    console.looh(error);
  }
}


async function handleCreateLogsFromAiResponse(text){
    try {
        const res = await fetch(
          "http://localhost/HabitFlow/Backend/AiAnalyze",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              user_id: user.id,
              text
            }),
          }
        );
        const data = await res.json();
        return data.data;
    } catch (error) {
        console.log(error)
    }
}


export async function getAllAiSummarys() {
  const user = JSON.parse(localStorage.getItem("user") || "null");
  const id = params.get("userId") ? Number(params.get("userId")) : user.id;
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/aiResponses", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        user_id: id,
      }),
    });
    const data = await res.json();
    return data.data
  } catch (error) {
    console.log(error);
  }
}


export async function getAllAiMeals() {
  const user = JSON.parse(localStorage.getItem("user") || "null");
  const id = params.get("userId") ? Number(params.get("userId")) : user.id;
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/aiMeals", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        user_id: id,
      }),
    });
    const data = await res.json();
    return data.data;
  } catch (error) {
    console.log(error);
  }
}


export async function deleteAiMeal(id) {
  try {
    const res = await fetch(
      "http://localhost/HabitFlow/Backend/aiMeals/delete",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id,
        }),
      }
    );
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
}

export async function deleteAiResponse(id) {
  try {
    const res = await fetch(
      "http://localhost/HabitFlow/Backend/aiResponse/delete",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id,
        }),
      }
    );
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
}