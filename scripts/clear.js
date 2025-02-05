let vehicleToClear = null

function openClearModal(vehicleId, vehicleRegNo) {
    const clearModal = document.getElementById("clearModal")
    const clearModalcontent = document.getElementById("clearModalcontent")
    vehicleToClear = vehicleId;
    clearModal.classList.add("active");
    clearModalcontent.classList.remove("hide");

    document.getElementById('clearVehicleRegNo').textContent = vehicleRegNo;
    
    // if (!hasPermission('clear_vehicle')) {
    //     alert('You do not have permission to delete vehicles');
    //     return;
    // }

}

function closeClearModal() {
    const clearModalcontent = document.getElementById("clearModalcontent")
    const clearModal = document.getElementById("clearModal");

    clearModalcontent.classList.add("hide")
    clearModalcontent.addEventListener('animationend', () => {
        clearModal.classList.remove("active")
    }, {once: true})
    vehicleToClear = null; 

    console.log("clear modal closed");
}

confirmClear.addEventListener("click", () => {
    if (vehicleToClear) {
        fetch(`clear_vehicle.php?id=${vehicleToClear}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`tr[data-vehicle-id="${vehicleToClear}"]`);
                    if (row) {
                        const statusCell = row.querySelector(`#status-${vehicleToClear}`);
                        if (statusCell) {
                            statusCell.innerHTML = '<span class="text-green-500 font-bold">✔ Cleared</span>';
                        }

                        const editButton = row.querySelector('button[onclick^="editVehicle"]');
                        if (editButton) {
                            editButton.disabled = true;
                            editButton.classList.add('opacity-50', 'cursor-not-allowed');
                        }

                        const clearButton = row.querySelector('button[onclick^="openClearModal"]');
                        if (clearButton) {
                            clearButton.disabled = true;
                            clearButton.classList.add('opacity-50', 'cursor-not-allowed');
                            clearButton.onclick = null;
                        }
                    }
                    
                    showAlert('Vehicle cleared successfully', 'success');
                } else {
                    throw new Error(data.message || 'Failed to clear vehicle');
                }
                closeClearModal();
            })
            .catch(error => {
                console.error("Error:", error);
                showAlert(error.message || 'An error occurred', 'error');
                closeClearModal();
            });
    }
});
