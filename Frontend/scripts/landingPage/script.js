const scrollButton = document.querySelector(".scroll-button");
const modalOverlay = document.getElementById("modal-overlay");
const closeModalBtn = document.getElementById("close-modal");

scrollButton.addEventListener("click", function () {
  modalOverlay.classList.add("active");
  document.body.classList.add("modal-open");
});

function closeModal() {
  modalOverlay.classList.remove("active");
  document.body.classList.remove("modal-open");
}

closeModalBtn.addEventListener("click", closeModal);

modalOverlay.addEventListener("click", function (e) {
  if (e.target === modalOverlay) {
    closeModal();
  }
});

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape" && modalOverlay.classList.contains("active")) {
    closeModal();
  }
});
