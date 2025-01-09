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
    
    if (!hasPermission('delete_vehicle')) {
        alert('You do not have permission to delete vehicles');
        return;
    }

}

function closeDeleteModal() {
    deleteModal.classList.add("hidden");
    vehicleToDelete = null; 
}

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !deleteModal.classList.contains("hidden")) {
        closeDeleteModal();
    }
});

function removeVehicleFromTable(vehicleId) {
    const row = document.querySelector(`tr[data-vehicle-id="${vehicleId}"]`);
    if (row) {
        row.remove();
        
        const tbody = document.querySelector('table tbody');
        if (!tbody.hasChildNodes()) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center p-4">No vehicles found</td>
                </tr>
            `;
        }
    }
}

confirmDelete.addEventListener("click", () => {
    if (vehicleToDelete) {
        fetch(`delete_vehicle.php?id=${vehicleToDelete}`, {
            method: "GET",
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                removeVehicleFromTable(vehicleToDelete);
                showAlert('Vehicle deleted successfully', 'success');
            } else {
                throw new Error(data.message || 'Failed to delete vehicle');
            }
            closeDeleteModal();
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert(error.message || 'An error occurred', 'error');
            closeDeleteModal();
        });
    }
});

cancelDelete.addEventListener("click", closeDeleteModal);

document.addEventListener("click", (e) => {
    if (e.target.matches(".delete-button") || e.target.closest(".delete-button")) {
        const button = e.target.matches(".delete-button") ? 
                      e.target : 
                      e.target.closest(".delete-button");
        const vehicleId = button.dataset.vehicleId;
        const vehicleRegNo = button.dataset.vehicleRegNo;
        openDeleteModal(vehicleId, vehicleRegNo);
    }
});
