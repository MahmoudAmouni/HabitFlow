const params = new URLSearchParams(window.location.search);

export async function getAllLogs() {
  const params = new URLSearchParams(window.location.search);
  const user = JSON.parse(localStorage.getItem("user") || "null");
  const id = Number(params.get("user_id")) ? Number(params.get("id")) : user.id;

  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/logs", {
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

// export async function getAllLogsByHabitId(habitId){
//   const user = JSON.parse(localStorage.getItem("user") || "null");
//   const id = user.id;

//   try {
//     const res = await fetch("http://localhost/HabitFlow/Backend/logs", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/json",
//       },
//       body: JSON.stringify({
//         habit_id: habitId,
//         user_id:id
//       }),
//     });
//     const data = await res.json();
//     return data.data;
//   } catch (error) {
//     console.log(error);
//   }
// }

export async function deleteLog(id) {
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/logs/delete", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id,
      }),
    });
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
}

export async function editLog(id,value) {
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/logs/update", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id,
        value
      }),
    });
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
}

export async function createLog(value, habit_id,date) {
  const user = JSON.parse(localStorage.getItem("user") || "null");
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/logs/create", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        habit_id,
        user_id: user.id,
        value,
        logged_at:date
      }),
    });
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
}


export async function createLogFromAiResponse(array_data) {
  const user = JSON.parse(localStorage.getItem("user") || "null");
  try {
    const res = await fetch(
      "http://localhost/HabitFlow/Backend/logs/createLogFromAiResponse",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          array_data
        }),
      }
    );
    const data = await res.json();
    console.log(data)
    return data;
  } catch (error) {
    console.log(error);
  }
}