import { getAllUsers } from "../Apis/users.js";
import { showToast } from "../components/toast.js";

const params = new URLSearchParams(window.location.search);
const admin = params.get("admin") || null;
const userLoalStorage = JSON.parse(localStorage.getItem("user") || null);

if( userLoalStorage.role !== "admin"){
  localStorage.removeItem("user");
  window.location.href = `http://localhost/HabitFlow/index.html`;
}

const usersContainer = document.getElementById("users-container");
usersContainer.innerHTML = "";

const table = document.createElement("table");
table.className = "users-table";

const thead = document.createElement("thead");
thead.innerHTML = `
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        `;
table.appendChild(thead);

async function renderUsers() {
  const tbody = document.createElement("tbody");
  const users = await getAllUsers();
  const nonAdminUsers = users.filter((user) => user.role !== "admin");
  nonAdminUsers.map((user) => {
    const row = document.createElement("tr");
    row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td class="user-actions">
                    <button class="user-btn delete-btn" data-id="${user.id}"><img src="../assets/images/trash.png" width="20" height="20" data-id="${user.id}"/></button>
                    <button class="user-btn check-user-status-btn" data-id=${user.id}><img src="../assets/images/arrow.png" width="20" height="20" data-id="${user.id}" /></button>
                </td>
            `;
    tbody.appendChild(row);
  });
  table.appendChild(tbody);
  usersContainer.appendChild(table);

  document.querySelectorAll(".delete-btn").forEach((button) => {
    button.addEventListener("click", handleDeleteClick);
  });
  document.querySelectorAll(".check-user-status-btn").forEach((button) => {
    button.addEventListener("click", goToUserPage);
  });
}

async function handleDeleteClick(e) {
  const userId = e.target.dataset.id;
  if (confirm("Are you sure u want to delete ?")) {
    const data = await deleteHabit(userId);
    renderHabits();
    showToast("User deleted successfully")
  }
}
async function goToUserPage(e){
    const userId = e.target.dataset.id;
    const adminsData = JSON.parse(localStorage.getItem("user") || null);
    window.location.href = `http://localhost/HabitFlow/Frontend/pages/home.html?id=${adminsData.id}&userId=${userId}`;
}


window.addEventListener("DOMContentLoaded", async () => await renderUsers());
