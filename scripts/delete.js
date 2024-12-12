// Variables to hold the modal and its buttons
const deleteModal = document.getElementById("deleteModal");
const confirmDelete = document.getElementById("confirmDelete");
const cancelDelete = document.getElementById("cancelDelete");

// Store the ID of the vehicle to delete
let vehicleToDelete = null;

// Open the modal
function openDeleteModal(vehicleId) {
    vehicleToDelete = vehicleId; // Store the vehicle ID
    deleteModal.classList.remove("hidden");
}

// Close the modal
function closeDeleteModal() {
    deleteModal.classList.add("hidden");
    vehicleToDelete = null; // Reset the vehicle ID
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

// Handle cancel button
cancelDelete.addEventListener("click", closeDeleteModal);

// Attach the openDeleteModal function to your delete buttons
// Example for dynamically added rows:
document.addEventListener("click", (e) => {
    if (e.target.matches(".delete-button")) {
        const vehicleId = e.target.dataset.vehicleId;
        openDeleteModal(vehicleId);
    }
});
