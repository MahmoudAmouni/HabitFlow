    // Get elements
    const scrollButton = document.querySelector('.scroll-button');
    const modalOverlay = document.getElementById('modal-overlay');
    const closeModalBtn = document.getElementById('close-modal');

    // Open modal when scroll button is clicked
    scrollButton.addEventListener('click', function() {
        modalOverlay.classList.add('active');
        document.body.classList.add('modal-open');
    });

    // Close modal functions
    function closeModal() {
        modalOverlay.classList.remove('active');
        document.body.classList.remove('modal-open');
    }

    // Close with close button
    closeModalBtn.addEventListener('click', closeModal);

    // Close when clicking outside the modal
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
            closeModal();
        }
    });