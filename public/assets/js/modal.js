const addBtns = document.querySelectorAll(".add-new-modal");
const modal = document.querySelector(".js-modal");
const modalContainer = document.querySelector(".js-modal-container");
const modalClose = document.querySelector(".js-modal-close");

console.log(addBtns);

function showModal() {
    modal.classList.add("open");
}

function hideModal() {
    modal.classList.remove("open");
}

for (const addBtn of addBtns) {
    addBtn.addEventListener("click", showModal);
}

modalClose.addEventListener("click", hideModal);
