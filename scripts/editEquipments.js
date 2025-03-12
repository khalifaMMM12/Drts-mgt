function openEditModal(equipmentId, type) {
    const modal = document.getElementById('EditequipmentModal');
    const modalContent = modal.querySelector('.modal-content');
    const form = document.getElementById('EditequipmentForm');
    const fieldsContainer = form.querySelector('#fields');
    
    // Fetch equipment details
    fetch(`get_equipment_data.php?id=${equipmentId}&type=${type}`)
        .then(response => response.json())
        .then(equipment => {
            modal.querySelector('#modalTitle').textContent = `Edit ${type.charAt(0).toUpperCase() + type.slice(1)}`;
            
            fieldsContainer.innerHTML = '';
            
            // Add hidden fields
            fieldsContainer.innerHTML += `
                <input type="hidden" name="id" value="${equipment.id}">
                <input type="hidden" name="type" value="${type}">
            `;
            
            // Generate fields based on equipment type
            switch(type) {
                case 'solar':
                    generateSolarFields(fieldsContainer, equipment);
                    break;
                case 'airConditioners':
                    generateAirConditionerFields(fieldsContainer, equipment);
                    break;
                // Add cases for other equipment types
            }
            
            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        })
        .catch(error => {
            console.error('Error fetching equipment details:', error);
            showAlert('Error loading equipment details', 'error');
        });
}

function generateSolarFields(container, equipment) {
    container.innerHTML += `
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Location</label>
            <input type="text" name="location" value="${equipment.location || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Capacity</label>
            <input type="text" name="capacity" value="${equipment.capacity || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Battery Type</label>
            <input type="text" name="battery_type" value="${equipment.battery_type || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Number of Batteries</label>
            <input type="number" name="no_of_batteries" value="${equipment.no_of_batteries || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Number of Panels</label>
            <input type="number" name="no_of_panels" value="${equipment.no_of_panels || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
    `;
}

// Add form submission handler
document.getElementById('EditequipmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);

    console.log('Form data being sent:', Object.fromEntries(formData));
    
    fetch('update_equipment.php', {
        method: 'POST',
        body: formData
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        console.log('Response status:', response.status);
        const data = await response.json();
        console.log('Response data:', data);
        
        if (!response.ok) {
            throw new Error(data.message || 'Update failed');
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            showAlert('Equipment updated successfully', 'success');
            closeEditModal();
            loadTableData(formData.get('type'));
        } else {
            showAlert(data.message || 'Error updating equipment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error updating equipment', 'error');
    });
});

function closeEditModal() {
    const modal = document.getElementById('EditequipmentModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}