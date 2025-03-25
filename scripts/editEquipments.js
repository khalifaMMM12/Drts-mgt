function openEditModal(equipmentId, type) {
    const modal = document.getElementById('EditequipmentModal');
    const modalContent = modal.querySelector('.modal-content');
    const form = document.getElementById('EditequipmentForm');
    const fieldsContainer = form.querySelector('#fields');
    
    // Fetch equipment details
    fetch(`get_equipment_data.php?id=${equipmentId}&type=${type}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(equipment => {
            modal.querySelector('#modalTitle').textContent = `Edit ${ type.charAt(0).toUpperCase() + type.slice(1)}`;
            
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
                case 'air_conditioners':
                    generateAirConditionerFields(fieldsContainer, equipment);
                    break;
                case 'fire_extinguishers':
                    generateFireExtinguisherFields(fieldsContainer, equipment);
                    break;
                case 'borehole':
                    generateBoreholeFields(fieldsContainer, equipment);
                    break;
                case 'generator':
                    generateGeneratorFields(fieldsContainer, equipment);
                    break;
                default:
                    console.error('Unknown equipment type:', type);
                    showAlert('Unknown equipment type', 'error');
            }
            
            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        })
        .catch(error => {
            console.error('Error details:', {
                message: error.message,
                stack: error.stack
            });
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
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Installation Date</label>
            <input type="date" name="installation_Date" value="${equipment.installation_Date || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Service Rendered</label>
            <input type="text" name="service_rendered" value="${equipment.service_rendered || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
    `;
}
function generateAirConditionerFields(container, equipment) {
    container.innerHTML += `
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Location</label>
            <input type="text" name="location" value="${equipment.location || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Model</label>
            <input type="text" name="model" value="${equipment.model || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Type</label>
            <input type="text" name="ac_type" value="${equipment.ac_type || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Number of Units</label>
            <input type="number" name="no_of_units" value="${equipment.no_of_units || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Capacity</label>
            <input type="text" name="capacity" value="${equipment.capacity || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
            <select name="status" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                <option value="Servicable" ${equipment.status === 'Servicable' ? 'selected' : ''}>Servicable</option>
                <option value="Unservicable" ${equipment.status === 'Unservicable' ? 'selected' : ''}>Unservicable</option>
            </select>
        </div>
    `;
}

function generateFireExtinguisherFields(container, equipment) {
    container.innerHTML += `
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Type</label>
            <input type="text" name="fe_type" value="${equipment.fe_type || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Weight</label>
            <input type="text" name="weight" value="${equipment.weight || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Amount</label>
            <input type="number" name="amount" value="${equipment.amount || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Location</label>
            <input type="text" name="location" value="${equipment.location || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
            <select name="status" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                 <option value="Servicable" ${equipment.status === 'Servicable' ? 'selected' : ''}>Servicable</option>
                <option value="Unservicable" ${equipment.status === 'Unservicable' ? 'selected' : ''}>Unservicable</option>
            </select>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Last Service Date</label>
            <input type="date" name="last_service_date" value="${equipment.last_service_date || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Expiration Date</label>
            <input type="date" name="expiration_date" value="${equipment.expiration_date || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
    `;
}

function generateBoreholeFields(container, equipment) {
    container.innerHTML += `
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Location</label>
            <input type="text" name="location" value="${equipment.location || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Model</label>
            <input type="text" name="model" value="${equipment.model || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
            <select name="status" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                 <option value="Servicable" ${equipment.status === 'Servicable' ? 'selected' : ''}>Servicable</option>
                <option value="Unservicable" ${equipment.status === 'Unservicable' ? 'selected' : ''}>Unservicable</option>
            </select>
        </div>
    `;
}

function generateGeneratorFields(container, equipment) {
    container.innerHTML += `
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Location</label>
            <input type="text" name="location" value="${equipment.location || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Model</label>
            <input type="text" name="model" value="${equipment.model || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Capacity</label>
            <input type="text" name="capacity" value="${equipment.capacity || ''}"
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
        </div>
        <div class="form-group">
            <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
            <select name="status" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                <option value="Servicable" ${equipment.status === 'Servicable' ? 'selected' : ''}>Servicable</option>
                <option value="Unservicable" ${equipment.status === 'Unservicable' ? 'selected' : ''}>Unservicable</option>
            </select>
        </div>
    `;
}

// Add form submission handler
document.getElementById('EditequipmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);

    const formDataObj = {};
    formData.forEach((value, key) => {
        formDataObj[key] = value;
    });

    console.log('Form data being sent:', formDataObj);
    
    fetch('update_equipment.php', {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const data = await response.json();
        console.log('Full server response:', {
            status: response.status,
            statusText: response.statusText,
            data: data
        });
        
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
            throw new Error(data.message || 'Error updating equipment');
        }
    })
    .catch(error => {
        console.error('Detailed error:', {
            message: error.message,
            stack: error.stack
        });
        showAlert(error.message || 'Error updating equipment', 'error');
    });
});

function validateFormData(formData) {
    const type = formData.get('type');
    const requiredFields = {
        solar: ['location', 'capacity', 'battery_type', 'no_of_batteries', 'no_of_panels', 'installation_Date', 'service_rendered'],
        air_conditioners: ['location', 'model', 'ac_type', 'no_of_units', 'capacity', 'status'],
        fire_extinguishers: ['fe_type', 'weight', 'amount', 'location', 'status', 'last_service_date', 'expiration_date'],
        borehole: ['location', 'model', 'status'],
        generator: ['location', 'model', 'status', 'capacity']
    };

    const fields = requiredFields[type] || [];
    const missing = fields.filter(field => !formData.get(field));
    
    if (missing.length > 0) {
        throw new Error(`Missing required fields: ${missing.join(', ')}`);
    }
    
    return true;
}

function closeEditModal() {
    const modal = document.getElementById('EditequipmentModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
