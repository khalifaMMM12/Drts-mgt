function openModal() {
    document.getElementById("vehicleModal").classList.remove("hidden");
}

function closeModal() {
    document.getElementById("vehicleModal").classList.add("hidden");
}

function toggleRepairType() {
    const repairField = document.getElementById("repairTypeField");
    const needsRepairsCheckbox = document.getElementById("needsRepairs");
    if (needsRepairsCheckbox.checked) {
        repairField.classList.remove("hidden");
    } else {
        repairField.classList.add("hidden");
    }
}

function showDetails(vehicleId) {
    // Fetch and display vehicle details (AJAX implementation can be added later for dynamic fetching)
    alert("Display details for vehicle ID: " + vehicleId);
    // Logic for opening and populating modal with vehicle info will go here
}

function showDetails(vehicleId) {
    fetch(`get_vehicle_details.php?id=${vehicleId}`)
        .then(response => response.json())
        .then(data => {
            const detailsModal = document.getElementById("detailsModal");
            const vehicleDetails = document.getElementById("vehicleDetails");

            vehicleDetails.innerHTML = `
                <p><strong>Registration No:</strong> ${data.reg_no}</p>
                <p><strong>Type:</strong> ${data.type}</p>
                <p><strong>Make:</strong> ${data.make}</p>
                <p><strong>Location:</strong> ${data.location}</p>
                <p><strong>Status:</strong> ${data.status}</p>
                <p><strong>Repair Type:</strong> ${data.repair_type || 'N/A'}</p>
                <p><strong>Inspection Date:</strong> ${data.inspection_date}</p>
                <p><strong>Repair Completion Date:</strong> ${data.repair_completion_date || 'N/A'}</p>
                <img src="../public/assets/${data.picture}" alt="Vehicle Image" class="mt-4 w-full rounded shadow">
            `;
            detailsModal.classList.remove("hidden");
        })
        .catch(error => {
            alert("Failed to load vehicle details");
            console.error(error);
        });
}

function closeDetailsModal() {
    document.getElementById("detailsModal").classList.add("hidden");
}

// Open Modal and populate it with vehicle details
    function openModal(vehicleId) {
        const vehicle = vehicles[vehicleId];
        document.getElementById('vehicleTitle').innerText = vehicle.title;
        document.getElementById('vehicleInfo').innerText = vehicle.info;
        
        // Populate images
        const imageGallery = document.getElementById('imageGallery');
        imageGallery.innerHTML = ''; // Clear existing images
        vehicle.images.forEach(image => {
            const imgElement = document.createElement('img');
            imgElement.src = `assets/${image}`;
            imgElement.alt = "Vehicle Image";
            imgElement.className = "w-32 h-32 object-cover rounded-md";
            imageGallery.appendChild(imgElement);
        });

        // Show the modal
        document.getElementById('vehicleModal').classList.remove('hidden');
    }

    // Close Modal
    function closeModal() {
        document.getElementById('vehicleModal').classList.add('hidden');
    }

    // Close Modal by clicking outside
    document.getElementById('vehicleModal').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) {
            closeModal();
        }
    });

