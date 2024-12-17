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
            document.getElementById("repair_completion_date").value = vehicle.repair_completion_date || "";
            document.getElementById("vehicleId").value = vehicle.id || "";

            // Update needs repairs checkbox
            const needsRepairsCheckbox = document.getElementById("needsRepairs");
            if (vehicle.status === "Needs Repairs") {
                needsRepairsCheckbox.checked = true;
                document.getElementById("repair_type").value = vehicle.repair_type || "";
            } else {
                needsRepairsCheckbox.checked = false;
            }
            toggleRepairType(); // Adjust visibility of the repair type field

            // Update repair type
            
            // Update image gallery
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
    if (confirm("Are you sure you want to delete this image?")) {
        fetch(`delete_image.php?vehicle_id=${vehicleId}&image=${image}`, { method: 'GET' })
            .then(response => response.json())
            .then(result => {
            if (result.success) {
                alert("Image deleted successfully!");
                editVehicle(vehicleId); 
            } else {
                alert("Failed to delete the image.");
            }
        })
        .catch(error => console.error('Error deleting image:', error));
    }
}


function submitEditForm() {
    const form = document.getElementById("editVehicleForm");
    const formData = new FormData(form);
    const vehicleId = document.getElementById("vehicleId").value;

    formData.append("vehicle_id", vehicleId);

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
            console.log("Updated Vehicle Data:", result.updatedVehicle);
            
            // Construct the vehicle object explicitly
            const vehicleToUpdate = {
                id: result.updatedVehicle.id,
                reg_no: result.updatedVehicle.reg_no,
                type: result.updatedVehicle.type,
                make: result.updatedVehicle.make,
                location: result.updatedVehicle.location,
                status: result.updatedVehicle.status || 
                        (result.updatedVehicle.needs_repairs ? "Needs Repairs" : "No Repairs"),
                inspection_date: result.updatedVehicle.inspection_date
            };

            closeEditModal();
            
            // Explicitly remove existing rows first
            const existingRows = document.querySelectorAll(`tr[data-vehicle-id="${vehicleId}"]`);
            console.log("Existing rows to remove:", existingRows.length);
            existingRows.forEach(row => row.remove());

            // Add the updated vehicle to the table
            addVehicleToTable(vehicleToUpdate);

            alert(result.message);
            form.reset();
        } else {
            console.error("Update failed:", result.error);
            alert("Failed to update vehicle: " + result.error);
        }
    })
    .catch(error => {
        console.error("Error updating vehicle", error);
        alert("An error occurred while updating the vehicle.");
    });
}

// Add event listener for form submission
document.getElementById("editVehicleForm").addEventListener("submit", function(event) {
    event.preventDefault();
    submitEditForm();
});


