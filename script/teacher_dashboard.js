const modal = document.getElementById("bookModal");
const openModalBtn = document.getElementById("openModalBtn");
const closeModalBtn = document.getElementById("closeModalBtn");

openModalBtn.onclick = function () {
  modal.style.display = "block";
};

closeModalBtn.onclick = function () {
  modal.style.display = "none";
};

window.onclick = function (event) {
  if (event.target === modal) {
    modal.style.display = "none";
  }
};

const assignModal = document.getElementById("assignBookModal");
const openAssignModalBtn = document.getElementById("openModalAssign");
const closeAssignModalBtn = document.getElementById("closeAssignModalBtn");

openAssignModalBtn.onclick = function () {
  assignModal.style.display = "block";
};

closeAssignModalBtn.onclick = function () {
  assignModal.style.display = "none";
};

window.onclick = function (event) {
  if (event.target === assignModal) {
    assignModal.style.display = "none";
  }
};
