    let userHabits = [
        { user_id: 1, habit_id: 101, habit_name: 'Running' },
        { user_id: 1, habit_id: 102, habit_name: 'Meditation' },
        { user_id: 2, habit_id: 201, habit_name: 'Reading' },
        { user_id: 2, habit_id: 202, habit_name: 'Water Intake' },
        { user_id: 3, habit_id: 301, habit_name: 'Yoga' },
        { user_id: 3, habit_id: 302, habit_name: 'Gym' },
    ];

    function renderUserHabits() {
        const container = document.getElementById('users-habits-container');
        container.innerHTML = '';

        const table = document.createElement('table');
        table.className = 'users-habits-table';

        const thead = document.createElement('thead');
        thead.innerHTML = `
            <tr>
                <th>User ID</th>
                <th>Habit ID</th>
                <th>Habit Name</th>
                <th>Actions</th>
            </tr>
        `;
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        userHabits.forEach(habit => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${habit.user_id}</td>
                <td>${habit.habit_id}</td>
                <td>${habit.habit_name}</td>
                <td class="action-cell">
                    <button class="delete-btn" data-id="${habit.habit_id}" data-user="${habit.user_id}">🗑️</button>
                </td>
            `;
            tbody.appendChild(row);
        });
        table.appendChild(tbody);

        container.appendChild(table);

    }


    window.addEventListener('DOMContentLoaded', renderUserHabits);