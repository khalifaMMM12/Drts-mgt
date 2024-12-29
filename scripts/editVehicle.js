// Edit Vehicle Model
function editVehicle(vehicleId) {
    console.log("Opening edit modal for vehicle ID:", vehicleId);

    const modal = document.getElementById("EditvehicleModal");
    const imageGallery = document.getElementById("editImagePreview");
    modal.classList.add("active");

    fetch(`get_vehicle_details.php?id=${vehicleId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(vehicle => {
            console.log("Vehicle data received:", vehicle);

            // Update form fields
            document.getElementById("reg_no").value = vehicle.reg_no || "";
            document.getElementById("type").value = vehicle.type || "";
            document.getElementById("make").value = vehicle.make || "";
            document.getElementById("location").value = vehicle.location || "";
            document.getElementById("inspection_date").value = vehicle.inspection_date || "";
            document.getElementById("vehicleId").value = vehicle.id || "";

            // Update needs repairs checkbox
            const needsRepairsCheckbox = document.getElementById("needsRepairs");
            const repairTypeField = document.getElementById("repairTypeField");
            const repairTypeTextarea = document.getElementById("repair_type");

            // Determine if repairs are needed with more robust checks
            const isNeedsRepairs = 
                vehicle.status === "Needs Repairs" || 
                vehicle.needs_repairs === 1 || 
                (vehicle.repair_type && vehicle.repair_type.trim() !== "") ||
                (vehicle.status && vehicle.status.toLowerCase().includes('repair'));

            // Set checkbox state and repair type field visibility
            needsRepairsCheckbox.checked = isNeedsRepairs;
            repairTypeField.classList.toggle("hidden", !isNeedsRepairs);
            repairTypeTextarea.value = vehicle.repair_type || "";

            console.log("Needs Repairs Status:", isNeedsRepairs);

            // Rest of your existing code for image gallery...
            imageGallery.innerHTML = ""; // Clear existing images
            const imagesArray = vehicle.images ? (typeof vehicle.images === "string" ? vehicle.images.split(",") : vehicle.images) : [];
            imagesArray.forEach((image, index) => {
                const sanitizedImage = image.trim().replace(/[^a-zA-Z0-9._-]/g, "");
                const imagePath = `../assets/vehicles/${sanitizedImage}`;

                const imageContainer = document.createElement("div");
                imageContainer.classList.add("relative", "group");

                const imgElement = document.createElement("img");
                imgElement.src = imagePath;
                imgElement.classList.add("cursor-pointer", "rounded", "shadow-lg");
                imgElement.onclick = () => openCarousel(index);

                const deleteIcon = document.createElement("span");
                deleteIcon.classList.add(
                    "absolute", "top-1", "right-1", "text-white", "text-2xl", "cursor-pointer", "opacity-0", "group-hover:opacity-100"
                );
                deleteIcon.innerHTML = "&times;";
                deleteIcon.onclick = () => {
                    if (confirm("Are you sure you want to delete this image?")) {
                        fetch(`delete_image.php?vehicle_id=${vehicleId}&image=${sanitizedImage}`, { method: "GET" })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    alert("Image deleted successfully!");
                                    imageContainer.remove();
                                } else {
                                    alert("Failed to delete the image.");
                                }
                            })
                            .catch(error => console.error("Error deleting image:", error));
                    }
                };

                imageContainer.appendChild(imgElement);
                imageContainer.appendChild(deleteIcon);
                imageGallery.appendChild(imageContainer);
            });

            console.log("Edit modal successfully populated.");
        })
        .catch(error => console.error("Error loading vehicle data:", error));
}

function updateImageGallery(images, vehicleId) {
    const imageGallery = document.getElementById("editImagePreview");
    imageGallery.innerHTML = "";

    const imagesArray = typeof images === "string" ? images.split(",") : images;
    
    if (imagesArray) {
        imagesArray.forEach((image, index) => {
            if (image.trim()) {
                const imageContainer = createImageContainer(image.trim(), index, vehicleId);
                imageGallery.appendChild(imageContainer);
            }
        });
    }
}

function createImageContainer(image, index, vehicleId) {
    const container = document.createElement("div");
    container.className = "relative group";
    container.setAttribute('data-image', image);

    const img = document.createElement("img");
    img.src = `../assets/vehicles/${image}`;
    img.className = "w-32 h-32 object-cover rounded-lg shadow-lg";

    const deleteButton = document.createElement("button");
    deleteButton.className = "absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10";
    deleteButton.innerHTML = '<i class="fas fa-times text-xs"></i>';
    deleteButton.type = "button";
    
    deleteButton.onclick = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (confirm('Are you sure you want to delete this image?')) {
            deleteImage(vehicleId, image);
        }
    };

    container.appendChild(img);
    container.appendChild(deleteButton);
    return container;
}

function closeEditModal() {
    console.log("Closing Edit Modal");
    document.getElementById("EditvehicleModal").classList.remove("active");
}

function uploadNewImage(vehicleId) {
    // Get the file input element
    const fileInput = document.getElementById("new_images");

    // Validate vehicle ID
    if (!vehicleId) {
        alert("Vehicle ID is missing.");
        return;
    }

    // Validate if files are selected
    if (fileInput.files.length === 0) {
        alert("Please select at least one image.");
        return;
    }

    // Create a FormData object and append vehicle ID
    const formData = new FormData();
    formData.append("vehicle_id", vehicleId);

    // Append selected files to FormData
    for (const file of fileInput.files) {
        formData.append("new_image[]", file); // Ensure this matches the PHP script's expected key
    }

    // Make the fetch API call
    fetch("upload_new_image.php", {
        method: "POST",
        body: formData,
    })
        .then(response => {
            // Check if the response is JSON
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            console.log("Server response:", result); // Debugging server response

            if (result.success) {
                alert("Image(s) uploaded successfully!");

                // Update the gallery with new images
                const newImages = result.new_images; // Array of newly uploaded images
                const imageGallery = document.getElementById("editImagePreview");

                newImages.forEach((image, index) => {
                    const sanitizedImage = image.trim().replace(/[^a-zA-Z0-9._-]/g, '');
                    const imagePath = `../assets/vehicles/${sanitizedImage}`;

                    // Create image container
                    const imageContainer = document.createElement("div");
                    imageContainer.classList.add("relative", "group");

                    // Create img element
                    const imgElement = document.createElement("img");
                    imgElement.src = imagePath;
                    imgElement.classList.add("cursor-pointer", "rounded", "shadow-lg");
                    imgElement.onclick = () => openCarousel(index); // Function for opening carousel

                    // Create delete icon
                    const deleteIcon = document.createElement("span");
                    deleteIcon.classList.add(
                        "absolute", "top-1", "right-1", "text-white", "text-2xl", "cursor-pointer", "opacity-0", "group-hover:opacity-100"
                    );
                    deleteIcon.innerHTML = "&times;";
                    deleteIcon.onclick = () => {
                        if (confirm("Are you sure you want to delete this image?")) {
                            fetch(`delete_image.php?vehicle_id=${vehicleId}&image=${sanitizedImage}`, { method: 'GET' })
                                .then(response => response.json())
                                .then(deleteResult => {
                                    if (deleteResult.success) {
                                        alert("Image deleted successfully!");
                                        imageContainer.remove();
                                    } else {
                                        alert("Failed to delete the image.");
                                    }
                                })
                                .catch(error => console.error("Error deleting image:", error));
                        }
                    };

                    // Append img and delete icon to the container
                    imageContainer.appendChild(imgElement);
                    imageContainer.appendChild(deleteIcon);

                    // Append container to the gallery
                    imageGallery.appendChild(imageContainer);
                });

                // Clear the file input
                fileInput.value = "";
            } else {
                // Show error message from server
                alert("Failed to upload image(s): " + result.error);
            }
        })
        .catch(error => {
            // Log any errors for debugging
            console.error("Error uploading images:", error);
            alert("An error occurred while uploading images. Please try again.");
        });
}


function deleteImage(vehicleId, image) {
    fetch(`delete_image.php?vehicle_id=${vehicleId}&image=${image}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const imageElement = document.querySelector(`[data-image="${image}"]`);
                if (imageElement) {
                    imageElement.remove();
                }
            } else {
                alert('Failed to delete image');
            }
        })
        .catch(error => console.error('Error:', error));
}

function submitEditForm() {
    const form = document.getElementById("editVehicleForm");
    const vehicleId = document.getElementById("vehicleId").value;
    
    console.log("Submitting form for Vehicle ID:", vehicleId);

    if (!vehicleId) {
        alert("Vehicle ID is missing. Please select a vehicle to edit.");
        return;
    }

    const formData = new FormData(form);
    const needsRepairsCheckbox = document.getElementById("needsRepairs");
    const repairTypeTextarea = document.getElementById("repair_type");

    // Explicitly set needs_repairs and status
    const needsRepairs = needsRepairsCheckbox.checked ? 1 : 0;
    const status = needsRepairs ? "Needs Repairs" : "No Repairs";
    const repairType = needsRepairs ? repairTypeTextarea.value.trim() : "";

    // Ensure vehicle_id is set
    formData.set('vehicle_id', vehicleId);
    formData.set('needs_repairs', needsRepairs);
    formData.set('status', status);
    formData.set('repair_type', repairType);

    // Log form data for debugging
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    fetch("edit_vehicle.php", {
        method: "POST",
        body: formData,
    })
    .then(response => {
        console.log("Response status:", response.status);
        return response.json();
    })
    .then(result => {
        console.log("Full server response:", result);

        if (result.success) {
            const vehicleToUpdate = {
                id: vehicleId,
                reg_no: formData.get('reg_no'),
                type: formData.get('type'),
                make: formData.get('make'),
                location: formData.get('location'),
                status: status,
                repair_type: repairType,
                inspection_date: formData.get('inspection_date')
            };

            closeEditModal();
            
            // Remove existing rows
            const existingRows = document.querySelectorAll(`tr[data-vehicle-id="${vehicleId}"]`);
            existingRows.forEach(row => row.remove());

            // Add updated vehicle to table
            addVehicleToTable(vehicleToUpdate);

            alert(result.message);
            form.reset();
        } else {
            console.error("Update failed:", result);
            alert(`Failed to update vehicle: ${result.error || 'Unknown error'}`);
        }
    })
    .catch(error => {
        console.error("Fetch error:", error);
        alert("An error occurred while updating the vehicle.");
    });
}

// Ensure the event listener is attached
document.addEventListener('DOMContentLoaded', () => {
    const needsRepairsCheckbox = document.getElementById("needsRepairs");
    if (needsRepairsCheckbox) {
        needsRepairsCheckbox.addEventListener("change", toggleRepairType);
    }
});

document.getElementById('editVehicleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const vehicleId = formData.get('vehicle_id');

    fetch('edit_vehicle.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const vehicle = {
                id: vehicleId,
                reg_no: formData.get('reg_no'),
                type: formData.get('type'),
                make: formData.get('make'),
                location: formData.get('location'),
                status: formData.get('needs_repairs') === 'on' ? 'Needs Repairs' : 'No Repairs',
                inspection_date: formData.get('inspection_date')
            };
            
            updateTableRow(vehicle);
            closeEditModal();
            showAlert('Vehicle updated successfully', 'success');
        } else {
            showAlert(data.message || 'Error updating vehicle', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error updating vehicle', 'error');
    });
});

function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    alertDiv.textContent = message;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

function updateTableRow(vehicle) {
    const row = document.querySelector(`tr[data-vehicle-id="${vehicle.id}"]`);
    if (!row) return;

    const getStatusBadge = (status) => {
        const badges = {
            'Fixed': `<span class="text-green-500 font-bold">✔ Cleared</span>`,
            'Needs Repairs': `<span class="text-yellow-600 font-bold">⚠ Needs Repairs</span>`
        };
        return badges[status] || `<span class="text-gray-500 font-bold">No Repairs</span>`;
    };

    row.innerHTML = `
        <td class="p-4 border-b">${vehicle.reg_no}</td>
        <td class="p-4 border-b">${vehicle.type}</td>
        <td class="p-4 border-b">${vehicle.make}</td>
        <td class="p-4 border-b">${vehicle.location}</td>
        <td class="p-4 border-b">${getStatusBadge(vehicle.status)}</td>
        <td class="p-4 border-b">${vehicle.inspection_date || 'N/A'}</td>
        <td class="p-4 border-b flex items-center justify-around space-x-2 text-lg">
            <button onclick="showDetails(${vehicle.id})" 
                    class="text-blue-500 hover:text-blue-700">ℹ</button>
            ${vehicle.status === 'Fixed' ? `
                <button class="text-yellow-500 opacity-50 cursor-not-allowed" disabled>
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button class="text-green-500 opacity-50 cursor-not-allowed" disabled>✔</button>
            ` : `
                <button onclick="editVehicle(${vehicle.id})" 
                        class="text-yellow-500 hover:text-yellow-700">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <a href="clear_vehicle.php?id=${vehicle.id}" 
                   class="text-green-500 hover:text-green-700">✔</a>
            `}
            <button onclick="openDeleteModal(${vehicle.id}, '${vehicle.reg_no}')"
                    class="text-red-500 hover:text-red-700">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </td>
    `;
}