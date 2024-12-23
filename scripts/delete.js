const deleteModal = document.getElementById("deleteModal");
const confirmDelete = document.getElementById("confirmDelete");
const cancelDelete = document.getElementById("cancelDelete");

let vehicleToDelete = null;

function openDeleteModal(vehicleId) {
    console.log("delete btn clicked");
    vehicleToDelete = vehicleId;
    deleteModal.classList.remove("hidden");
    deleteModal.classList.add("active");
    
}

function closeDeleteModal() {
    deleteModal.classList.add("hidden");
    vehicleToDelete = null; 
}

// Handle delete confirmation
confirmDelete.addEventListener("click", () => {
    if (vehicleToDelete) {
        // Make an AJAX call to delete the vehicle
        fetch(`delete_vehicle.php?id=${vehicleToDelete}`, {
            method: "GET",
        })
        .then(response => response.text())
        .then(data => {
            // Refresh the table or remove the deleted row
            console.log(data); // Handle response
            closeDeleteModal();
        })
        .catch(error => console.error("Error:", error));
    }
});

// cancelDelete.addEventListener("click", closeDeleteModal);

document.addEventListener("click", (e) => {
    if (e.target.matches(".delete-button")) {
        const vehicleId = e.target.dataset.vehicleId;
        openDeleteModal(vehicleId);
    }
});
