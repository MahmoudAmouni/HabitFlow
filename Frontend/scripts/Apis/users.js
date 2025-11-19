const params = new URLSearchParams(window.location.search);

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
    return data
  } catch (error) {
    console.log(error);
  }
}

export async function getUserById() {
  try {
    const user = JSON.parse(localStorage.getItem("user") || "null");
    const id = params.get("userId") ? Number(params.get("userId")) : user.id;
    const res = await fetch(`http://localhost/HabitFlow/Backend/users?id=${id}`);
    const data = await res.json();
    console.log(data.data);
    return data.data;
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
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
}


export async function updateUser(name,email, password) {
  const user = JSON.parse(localStorage.getItem("user") || "null");
  const id = user.id
  try {
    const res = await fetch(
      `http://localhost/HabitFlow/Backend/users/update?&id=${id}`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          name,
          email,
          password,
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

export async function getAllUsers() {
  const user = JSON.parse(localStorage.getItem("user") || "null");
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/users");
    const data = await res.json();
    return data.data;
  } catch (error) {
    console.log(error);
  }
}

export async function deleteUser(id) {
  try {
    const res = await fetch("http://localhost/HabitFlow/Backend/users/delete", {
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
