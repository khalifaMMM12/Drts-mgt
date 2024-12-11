// Wait for the DOM to load
document.addEventListener("DOMContentLoaded", function () {
    const equipmentSelect = document.getElementById("equipmentSelect");
    const solarTable = document.getElementById("solarTable");
    const airConditionersTable = document.getElementById("airConditionersTable");
    const fireExtinguishersTable = document.getElementById("fireExtinguishersTable");
    const boreholeTable = document.getElementById("boreholeTable");
    const generatorTable = document.getElementById("generatorTable");

    const addEquipmentButton = document.getElementById("addEquipmentButton");
    const addEquipmentModal = document.getElementById("addEquipmentModal");
    const addEquipmentForm = document.getElementById("addEquipmentForm");
    const cancelButton = document.getElementById("cancelButton");

    const modalTitle = document.getElementById("modalTitle");
    const fields = document.getElementById("fields");
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
        boreholeTable.classList.add("hidden");
        generatorTable.classList.add("hidden");

        if (type === "solar") {
            solarTable.classList.remove("hidden");
        } else if (type === "airConditioners") {
            airConditionersTable.classList.remove("hidden");
        } else if (type === "fireExtinguishers") {
            fireExtinguishersTable.classList.remove("hidden");
        }else if (type === "borehole"){
            boreholeTable.classList.remove("hidden")
        }else if (type === "generator"){
            generatorTable.classList.remove("hidden")
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
        fields.innerHTML = "";

        if (type === "solar") {
            fields.innerHTML = `
                <!-- Solar Equipment Form Fields -->
                <div>
                    <label for="location" class="block">Location</label>
                    <input type="text" id="location" name="location" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="capacity" class="block">Capacity</label>
                    <input type="text" id="capacity" name="capacity" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="batteryType" class="block">Battery Type</label>
                    <input type="text" id="batteryType" name="batteryType" required class="border p-2 w-full mb-4>
                </div>
                <div>
                    <label for="noOfBatteries" class="block">No. of Batteries</label>
                    <input type="number" id="noOfBatteries" name="noOfBatteries" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="noOfPanels" class="block">No. of Panels</label>
                    <input type="number" id="noOfPanels" name="noOfPanels" required class="border p-2 w-full mb-4">
                </div>
            `;
        } else if (type === "airConditioners") {
            fields.innerHTML = `
                <!-- Air Conditioners Form Fields -->
                <div>
                    <label for="location" class="block">Location</label>
                    <input type="text" id="location" name="location" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="model" class="block">Model</label>
                    <input type="text" id="model" name="model" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="type" class="block">Type</label>
                    <input type="text" id="type" name="type" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="noOfUnits" class="block">No. of Units</label>
                    <input type="number" id="noOfUnits" name="noOfUnits" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="capacity" class="block">Capacity</label>
                    <input type="text" id="capacity" name="capacity" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="status" class="block">Status</label>
                    <select id="status" name="status" class="border p-2 w-full mb-4">
                        <option value="Operational">Operational</option>
                        <option value="Not Operational">Not Operational</option>
                    </select>
                </div>
            `;
        } else if (type === "fireExtinguishers") {
            fields.innerHTML = `
                <!-- Fire Extinguishers Form Fields -->
                <div>
                    <label for="location" class="block">Location</label>
                    <input type="text" id="location" name="location" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="type" class="block">Type</label>
                    <input type="text" id="type" name="type" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="weight" class="block">Weight</label>
                    <input type="text" id="weight" name="weight" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="amount" class="block">Amount</label>
                    <input type="number" id="amount" name="amount" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="lastServiceDate" class="block">Last service date</label>
                    <input type="date" id="lastServiceDate" name="lastServiceDate" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="expirationDate" class="block">Expiration date</label>
                    <input type="date" id="expirationDate" name="expirationDate" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="status" class="block">Status</label>
                    <select id="status" name="status" class="border p-2 w-full mb-4">
                        <option value="Operational">Operational</option>
                        <option value="Not Operational">Not Operational</option>
                    </select>
                </div>
            `;
        }else if (type === "borehole"){
            fields.innerHTML = `
                <!-- borehole Equipment Form Fields -->
                <div>
                    <label for="location" class="block">Location</label>
                    <input type="text" id="location" name="location" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="model" class="block">Model</label>
                    <input type="text" id="model" name="model" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="status" class="block">Status</label>
                    <select id="status" name="status" class="border p-2 w-full mb-4">
                        <option value="Operational">Operational</option>
                        <option value="Not Operational">Not Operational</option>
                    </select>
                </div>
            `;
        }else if (type === "generator"){
            fields.innerHTML = `
                <!-- generator Equipment Form Fields -->
                <div>
                    <label for="location" class="block">Location</label>
                    <input type="text" id="location" name="location" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="model" class="block">Model</label>
                    <input type="text" id="model" name="model" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="noOfUnits" class="block">Units</label>
                    <input type="text" id="noOfUnits" name="noOfUnits" required class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="status" class="block">Status</label>
                    <select id="status" name="status" class="border p-2 w-full mb-4">
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
            :type === "borehole"
            ? "Borehole"
            :type === "generator"
            ? "Generator"
            : "Solar";
    }
});

function closeModal() {
    console.log("Closing Modal");
    addEquipmentModal.classList.add("hidden");
    addEquipmentForm.reset();
}

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
            console.log('Fetched data:', data);  // Log the data for debugging

            const tableBody = document.getElementById(`${type}Data`);
            if (!tableBody) {
                console.error(`Table body with id ${type}Data not found.`);
                return;
            }

            tableBody.innerHTML = "";

            if (data.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center p-4 text-yellow-500">No equipment found</td></tr>`;
                return;
            }

            data.forEach((equipment) => {
                const newRow = document.createElement('tr');
                newRow.classList.add('border-b');

                if (type === 'solar') {
                    newRow.innerHTML = `
                        <td class="p-4">${equipment.location || 'N/A'}</td>
                        <td class="p-4">${equipment.capacity || 'N/A'}</td>
                        <td class="p-4">${equipment.battery_type || 'N/A'}</td>
                        <td class="p-4">${equipment.no_of_batteries || 'N/A'}</td>
                        <td class="p-4">${equipment.no_of_panels || 'N/A'}</td>
                        <td class="p-4">${equipment.date_added || 'N/A'}</td>
                        <td class="p-4">            
                            <a href="#" class="text-red-500 hover:text-red-700" onclick="deleteEquipment(${equipment.id}, 'solar')"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    `;
                } else if (type === 'airConditioners') {
                    newRow.innerHTML = `
                        <td class="p-4">${equipment.location || 'N/A'}</td>
                        <td class="p-4">${equipment.model || 'N/A'}</td>
                        <td class="p-4">${equipment.type || 'N/A'}</td>
                        <td class="p-4">${equipment.no_of_units || 'N/A'}</td>
                        <td class="p-4">${equipment.capacity || 'N/A'}</td>
                        <td class="p-4">${equipment.status || 'N/A'}</td>
                        <td class="p-4">            
                            <a href="#" class="text-red-500 hover:text-red-700" onclick="deleteEquipment(${equipment.id}, 'airConditioners')"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
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
                        <td class="p-4">            
                            <a href="#" class="text-red-500 hover:text-red-700" onclick="deleteEquipment(${equipment.id}, 'fireExtinguishers')"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    `;
                } else if (type === 'borehole'){
                    newRow.innerHTML = `
                        <td class="p-4">${equipment.location || 'N/A'}</td>
                        <td class="p-4">${equipment.model || 'N/A'}</td>
                        <td class="p-4">${equipment.status || 'N/A'}</td>
                        <td class="p-4">            
                            <a href="#" class="text-red-500 hover:text-red-700" onclick="deleteEquipment(${equipment.id}, 'borehole')"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    `;
                }else if (type === 'generator'){
                    newRow.innerHTML = `
                        <td class="p-4">${equipment.location || 'N/A'}</td>
                        <td class="p-4">${equipment.model || 'N/A'}</td>
                        <td class="p-4">${equipment.status || 'N/A'}</td>
                        <td class="p-4">${equipment.noOfUnits || 'N/A'}</td>
                        <td class="p-4">            
                            <a href="#" class="text-red-500 hover:text-red-700" onclick="deleteEquipment(${equipment.id}, 'generator')"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    `;
                }

                tableBody.appendChild(newRow);
            });
        })
        .catch((error) => {
            console.error("Error:", error);
            const tableBody = document.getElementById(`${type}Data`);
            if (tableBody) {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center p-4 text-red-500">Error fetching data: ${error.message}</td></tr>`;
            }
        });
}

loadTableData('solar');

function deleteEquipment(equipmentId, equipmentType) {
    if (!confirm('Are you sure you want to delete this equipment?')) {
        return;
    }

    fetch('delete_equipment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: equipmentId,    
            type: equipmentType     
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response Text:', text);
                throw new Error('Server Error: ' + text);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            alert("Equipment deleted successfully.");
            loadTableData(equipmentType);
        } else {
            alert("Error deleting equipment: " + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}



