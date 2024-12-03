// Wait for the DOM to load
document.addEventListener("DOMContentLoaded", function () {
    const equipmentSelect = document.getElementById("equipmentSelect");
    const solarTable = document.getElementById("solarTable");
    const airConditionersTable = document.getElementById("airConditionersTable");
    const fireExtinguishersTable = document.getElementById("fireExtinguishersTable");

    const addEquipmentButton = document.getElementById("addEquipmentButton");
    const addEquipmentModal = document.getElementById("addEquipmentModal");
    const addEquipmentForm = document.getElementById("addEquipmentForm");
    const cancelButton = document.getElementById("cancelButton");

    const modalTitle = document.getElementById("modalTitle");
    const additionalFields = document.getElementById("additionalFields");
    const equipmentTypeInput = document.getElementById("equipmentType");

    // Show the first table (Solar) by default
    showTable("solar");

    // Event listener for equipment selection
    equipmentSelect.addEventListener("change", function () {
        showTable(this.value);
    });

    // Show table based on selected equipment type
    function showTable(type) {
        solarTable.classList.add("hidden");
        airConditionersTable.classList.add("hidden");
        fireExtinguishersTable.classList.add("hidden");

        if (type === "solar") {
            solarTable.classList.remove("hidden");
        } else if (type === "airConditioners") {
            airConditionersTable.classList.remove("hidden");
        } else if (type === "fireExtinguishers") {
            fireExtinguishersTable.classList.remove("hidden");
        }

        loadTableData(type);
    }

    // Show modal when Add Equipment is clicked
    addEquipmentButton.addEventListener("click", function () {
        const selectedType = equipmentSelect.value;
        equipmentTypeInput.value = selectedType;
        modalTitle.textContent = `Add ${formatTitle(selectedType)} Details`;
        populateFormFields(selectedType);
        addEquipmentModal.classList.remove("hidden");
    });

    // Hide modal when Cancel is clicked
    cancelButton.addEventListener("click", function () {
        addEquipmentModal.classList.add("hidden");
        addEquipmentForm.reset();
    });

    // Populate form fields dynamically based on equipment type
    function populateFormFields(type) {
        additionalFields.innerHTML = "";

        if (type === "solar") {
            additionalFields.innerHTML = `
                <!-- Solar Equipment Form Fields -->
                <div class="mb-4">
                    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                    <input type="text" id="capacity" name="capacity" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="batteryType" class="block text-sm font-medium text-gray-700">Battery Type</label>
                    <input type="text" id="batteryType" name="batteryType" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="noOfBatteries" class="block text-sm font-medium text-gray-700">No. of Batteries</label>
                    <input type="number" id="noOfBatteries" name="noOfBatteries" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="noOfPanels" class="block text-sm font-medium text-gray-700">No. of Panels</label>
                    <input type="number" id="noOfPanels" name="noOfPanels" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
            `;
        } else if (type === "airConditioners") {
            additionalFields.innerHTML = `
                <!-- Air Conditioners Form Fields -->
                <div class="mb-4">
                    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                    <input type="text" id="model" name="model" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <input type="text" id="type" name="type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="noOfUnits" class="block text-sm font-medium text-gray-700">No. of Units</label>
                    <input type="number" id="noOfUnits" name="noOfUnits" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                    <input type="text" id="capacity" name="capacity" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="Operational">Operational</option>
                        <option value="Not Operational">Not Operational</option>
                    </select>
                </div>
            `;
        } else if (type === "fireExtinguishers") {
            additionalFields.innerHTML = `
                <!-- Fire Extinguishers Form Fields -->
                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <input type="text" id="type" name="type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="weight" class="block text-sm font-medium text-gray-700">Weight</label>
                    <input type="text" id="weight" name="weight" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" id="amount" name="amount" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="lastServiceDate" class="block text-sm font-medium text-gray-700">Last service date</label>
                    <input type="date" id="lastServiceDate" name="lastServiceDate" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="expirationDate" class="block text-sm font-medium text-gray-700">Expiration date</label>
                    <input type="date" id="expirationDate" name="expirationDate" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="Operational">Operational</option>
                        <option value="Not Operational">Not Operational</option>
                    </select>
                </div>
            `;
        }
    }

    function formatTitle(type) {
        return type === "airConditioners"
            ? "Air Conditioners"
            : type === "fireExtinguishers"
            ? "Fire Extinguishers"
            : "Solar";
    }
});


addEquipmentForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(addEquipmentForm);

    fetch("insert_equipments.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.text()) // Change from .json() to .text() to capture the raw response
        .then((data) => {
            console.log("Raw Response:", data); // Log the response to the console
            return JSON.parse(data); // Then attempt to parse as JSON
        })
        .then((data) => {
            if (data.success) {
                alert("Equipment added successfully!");
                addEquipmentModal.classList.add("hidden");
                addEquipmentForm.reset();
                loadTableData(equipmentSelect.value);
            } else {
                alert("Error adding equipment: " + data.error);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
    
    
});

// Load table data
function loadTableData(type) {
    fetch(`get_equipment_data.php?type=${type}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById(`${type}Data`);
            tableBody.innerHTML = "";

            data.forEach((equipment) => {
                const newRow = document.createElement('tr');
                newRow.classList.add('border-b');

                // Dynamically render each table row based on the equipment type
                if (type === 'solar') {
                    newRow.innerHTML = `
                        <td class="p-4">${equipment.location || 'N/A'}</td>
                        <td class="p-4">${equipment.capacity || 'N/A'}</td>
                        <td class="p-4">${equipment.battery_type || 'N/A'}</td>
                        <td class="p-4">${equipment.no_of_batteries || 'N/A'}</td>
                        <td class="p-4">${equipment.no_of_panels || 'N/A'}</td>
                        <td class="p-4">${equipment.date_added || 'N/A'}</td>
                        <td class="p-4">${equipment.service_rendered || 'N/A'}</td>
                    `;
                } else if (type === 'airConditioners') {
                    newRow.innerHTML = `
                        <td class="p-4">${equipment.location || 'N/A'}</td>
                        <td class="p-4">${equipment.model || 'N/A'}</td>
                        <td class="p-4">${equipment.type || 'N/A'}</td>
                        <td class="p-4">${equipment.no_of_units || 'N/A'}</td>
                        <td class="p-4">${equipment.capacity || 'N/A'}</td>
                        <td class="p-4">${equipment.status || 'N/A'}</td>
                    `;
                } else if (type === 'fireExtinguishers') {
                    newRow.innerHTML = `
                        <td class="p-4">${equipment.type || 'N/A'}</td>
                        <td class="p-4">${equipment.weight || 'N/A'}</td>
                        <td class="p-4">${equipment.amount || 'N/A'}</td>
                        <td class="p-4">${equipment.location || 'N/A'}</td>
                        <td class="p-4">${equipment.status || 'N/A'}</td>
                        <td class="p-4">${equipment.last_service_date || 'N/A'}</td>
                        <td class="p-4">${equipment.expiration_date || 'N/A'}</td>
                    `;
                }

                tableBody.appendChild(newRow);
            });
        })
        .catch((error) => {
            console.error("Error:", error);
            const tableBody = document.getElementById(`${type}Data`);
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center p-4 text-red-500">Error fetching data: ${error.message}</td></tr>`;
        });
}



// Initial table load
loadTableData('solar');

