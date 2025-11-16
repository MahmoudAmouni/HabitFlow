    let users = [
        { id: 1, name: 'jazzar', email: 'jazzar@example.com' },
        { id: 2, name: 'kazz', email: 'kazz@example.com' },
        { id: 3, name: 'mahmoud', email: 'mahmoud@example.com' },
        { id: 4, name: 'Buman', email: 'buman@example.com' },
    ];

    function renderUsers() {
        const usersContainer = document.getElementById('users-container');
        usersContainer.innerHTML = '';

        const table = document.createElement('table');
        table.className = 'users-table';

        const thead = document.createElement('thead');
        thead.innerHTML = `
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        `;
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        users.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td class="user-actions">
                    <button class="user-btn delete-btn" data-id="${user.id}">🗑️</button>
                </td>
            `;
            tbody.appendChild(row);
        });
        table.appendChild(tbody);

        usersContainer.appendChild(table);
    }

    window.addEventListener('DOMContentLoaded', renderUsers);