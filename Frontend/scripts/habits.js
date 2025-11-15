    // Dummy data for habits
    let habits = [
        { id: 1, name: 'Running'},
        { id: 2, name: 'Meditation'},
        { id: 3, name: 'Reading'},
        { id: 4, name: 'Water Intake'}
    ];

    function renderHabits() {
        const habitsContainer = document.getElementById('habits-container');
        habitsContainer.innerHTML = '';

        habits.forEach(habit => {
            const habitItem = document.createElement('div');
            habitItem.className = 'habit-item';
            habitItem.innerHTML = `
                <div class="habit-name">${habit.name}</div>
                <div class="habit-actions">
                    <button class="habit-btn edit-btn" data-id="${habit.id}">✏️</button>
                    <button class="habit-btn delete-btn" data-id="${habit.id}">🗑️</button>
                </div>
            `;
            habitsContainer.appendChild(habitItem);
        });
    }

    document.getElementById('add-habit-btn').addEventListener('click', function() {
        document.getElementById('modal-overlay').classList.add('active');
        document.body.classList.add('modal-open');
        document.getElementById('habit-name').value = '';
        document.getElementById('habit-unit').value = '';
        document.getElementById('habit-target').value = '';
    });

    function closeModal() {
        document.getElementById('modal-overlay').classList.remove('active');
        document.body.classList.remove('modal-open');
    }

    document.getElementById('cancel-habit-btn').addEventListener('click', closeModal);

    document.getElementById('save-habit-btn').addEventListener('click', function() {
        const name = document.getElementById('habit-name').value.trim();
        const unit = document.getElementById('habit-unit').value.trim();
        const target = parseInt(document.getElementById('habit-target').value);

        if (!name || !unit || isNaN(target)) {
            alert('Please fill all fields correctly');
            return;
        }

        const newHabit = {
            id: Date.now(),
            name: name,
            unit: unit,
            target: target
        };

        habits.push(newHabit);
        closeModal();
        renderHabits();
    });

    document.getElementById('modal-overlay').addEventListener('click', function(e) {
        if (e.target === document.getElementById('modal-overlay')) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('modal-overlay').classList.contains('active')) {
            closeModal();
        }
    });

    window.addEventListener('DOMContentLoaded', renderHabits);