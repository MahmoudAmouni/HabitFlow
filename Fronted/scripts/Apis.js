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
    console.log(data)
  } catch (error) {
    console.log(error);
  }
}
