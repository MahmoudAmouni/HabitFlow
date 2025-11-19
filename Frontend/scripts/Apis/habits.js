const params = new URLSearchParams(window.location.search);
const user = JSON.parse(localStorage.getItem("user") || "null");

export async function createHabit(name, unit, target) {
  try {
    const res = await fetch(
      "http://localhost/HabitFlow/Backend/habits/create",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          user_id: user.id,
          name,
          unit,
          target,
        }),
      }
    );
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
}

export async function getAllHabits() {
  const id = params.get("userId") ? Number(params.get("userId")) : user.id;

  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/habits", {
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

export async function editHabit(name, unit, target, id) {
  try {
    const res = await fetch(
      "http://localhost/HabitFlow/Backend/habits/update",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id,
          name,
          unit,
          target,
        }),
      }
    );
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
}

export async function deleteHabit(id) {
  try {
    const res = await fetch(
      "http://localhost/HabitFlow/Backend/habits/delete",
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
