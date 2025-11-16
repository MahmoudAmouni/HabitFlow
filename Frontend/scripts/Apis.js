export async function createUser(
  username,
  email,
  password,
  height,
  weight,
  gender
) {
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/users/create", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        name: username,
        email,
        password,
        height,
        weight,
        gender,
      }),
    });
    const data = await res.json();
    console.log(data);
  } catch (error) {
    console.log(error);
  }
}

export async function getUserByEmail(email, password) {
  try {
    const res = await fetch(
      "http://localhost/HabitFlow/Backend/users/byemail",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          email,
          password,
        }),
      }
    );
    const data = await res.json()
    return data.data
  } catch (error) {
    console.log(error);
  }
}

export async function createHabit(name,unit,target){
  const user = JSON.parse(localStorage.getItem("user") || "null");
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/habits/create", {
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
    });
    const data = await res.json();
    return data
  } catch (error) {
    console.log(error)
  }
}

export async function getAllHabits(){
   const user = JSON.parse(localStorage.getItem("user") || "null");
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/habits", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        user_id:user.id,
      }),
    });
    const data = await res.json()
    return data.data;
  } catch (error) {
    console.log(error)
  }
}

export async function editHabit(name,unit,target,id){
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
    const data = await res.json()
    return data
  } catch (error) {
    console.log(error)
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
          id
        }),
      }
    );
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
}



export async function getAllLogs(){
  const user = JSON.parse(localStorage.getItem("user") || "null");
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/logs", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        user_id: user.id,
      }),
    });
    const data = await res.json();
    return data.data;
  } catch (error) {
    console.log(error);
  }
}


export async function deleteLog(id) {
  try {
    const res = await fetch(
      "http://localhost/HabitFlow/Backend/logs/delete",
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


export async function createLog(value,habit_id) {
  const user = JSON.parse(localStorage.getItem("user") || "null");
  try {
    const res = await fetch(
      "http://localhost/HabitFlow/Backend/logs/create",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          habit_id,
          user_id: user.id,
          value,
        }),
      }
    );
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
}





