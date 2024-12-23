const deleteModal = document.getElementById("deleteModal");
const confirmDelete = document.getElementById("confirmDelete");
const cancelDelete = document.getElementById("cancelDelete");

let vehicleToDelete = null;

function openDeleteModal(vehicleId, vehicleRegNo) {
    console.log("delete btn clicked");
    vehicleToDelete = vehicleId;
    deleteModal.classList.remove("hidden");
    deleteModal.classList.add("active");

    document.getElementById('deleteVehicleRegNo').textContent = vehicleRegNo;


}

function closeDeleteModal() {
    deleteModal.classList.add("hidden");
    vehicleToDelete = null; 
}

confirmDelete.addEventListener("click", () => {
    if (vehicleToDelete) {
        fetch(`delete_vehicle.php?id=${vehicleToDelete}`, {
            method: "GET",
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); 
            closeDeleteModal();
        })
        .catch(error => console.error("Error:", error));
    }
});

cancelDelete.addEventListener("click", closeDeleteModal);

document.addEventListener("click", (e) => {
    if (e.target.matches(".delete-button")) {
        const vehicleId = e.target.dataset.vehicleId;
        const vehicleRegNo = e.target.dataset.vehicleRegNo;
        openDeleteModal(vehicleId, vehicleRegNo);
    }
});
