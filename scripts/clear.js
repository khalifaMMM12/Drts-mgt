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
        fetch(`clear_vehicle.php?id=${vehicleToClear}`, {
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
                // removeVehicleFromTable(vehicleToDelete);
                showAlert('Vehicle Cleared successfully', 'success');
            } else {
                throw new Error(data.message || 'Failed to Clear vehicle');
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