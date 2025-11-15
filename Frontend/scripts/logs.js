    // Dummy data
    const habits = ['Running', 'Meditation', 'Reading'];
    const dates = [
        '2025-11-16',
        '2025-11-15',
        '2025-11-14',
    ];

    let logs = [
        { id: 1, habit: 'Running', date: '2025-11-16', text: 'Running 3kms' },
        { id: 2, habit: 'Meditation', date: '2025-11-15', text: 'Meditated for 20 minutes' },
        { id: 3, habit: 'Reading', date: '2025-11-14', text: 'Read 30 pages of "Atomic Habits"' },
        { id: 4, habit: 'Running', date: '2025-11-13', text: 'Running 5kms in rain' }
    ];

    function populateSelects() {
        const habitSelect = document.getElementById('habit-select');
        const dateSelect = document.getElementById('date-select');

        habits.forEach(habit => {
            const option = document.createElement('option');
            option.value = habit;
            option.textContent = habit;
            habitSelect.appendChild(option);
        });

        dates.forEach(date => {
            const option = document.createElement('option');
            option.value = date;
            option.textContent = new Date(date).toLocaleDateString();
            dateSelect.appendChild(option);
        });
    }

    function renderLogs() {
      const logsContainer = document.getElementById("logs-container");
      logsContainer.innerHTML = "";

      logs.forEach((log) => {
        const logItem = document.createElement("div");
        logItem.className = "log-item";
        logItem.innerHTML = `
                <div class="log-text">${
                  log.text
                } <span style="color: rgba(255,255,255,0.5); font-size: 0.85rem;">(${
          log.habit
        } • ${new Date(log.date).toLocaleDateString()})</span></div>
                <div class="log-actions">
                    <button class="log-btn edit-btn" data-id="${
                      log.id
                    }">✏️</button>
                    <button class="log-btn delete-btn" data-id="${
                      log.id
                    }">🗑️</button>
                </div>
            `;
        logsContainer.appendChild(logItem);
      });
    }
    document.getElementById('add-log-btn').addEventListener('click', function() {
        const habit = document.getElementById('habit-select').value;
        const date = document.getElementById('date-select').value;
        const text = document.getElementById('log-input').value.trim();

        if (!text) {
            alert('Please enter a log entry');
            return;
        }

        const newLog = {
            id: Date.now(),
            habit: habit,
            date: date,
            text: text
        };

        logs.push(newLog);
        document.getElementById('log-input').value = '';
        renderLogs();
    });


    window.addEventListener('DOMContentLoaded', function() {
        populateSelects();
        renderLogs();
    });