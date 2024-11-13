// function openModal() {
//     document.getElementById("vehicleModal").classList.remove("hidden");
// }

// function closeModal() {
//     document.getElementById("vehicleModal").classList.add("hidden");
// }


function toggleRepairType() {
    const repairField = document.getElementById("repairTypeField");
    const needsRepairsCheckbox = document.getElementById("needsRepairs");
    repairField.classList.toggle("hidden", !needsRepairsCheckbox.checked);
}


// function showDetails(vehicleId) {
//     // Fetch and display vehicle details (AJAX implementation can be added later for dynamic fetching)
//     alert("Display details for vehicle ID: " + vehicleId);
//     // Logic for opening and populating modal with vehicle info will go here
// }


function openModal() {
    const modal = document.getElementById("vehicleModal");
    const modalContent = document.getElementById("vehicleModalContent");
  
    modal.classList.add("active");
    modalContent.classList.remove("hide");
}
  
  function closeModal() {
    const modal = document.getElementById("vehicleModal");
    const modalContent = document.getElementById("vehicleModalContent");
  
    modalContent.classList.add("hide");
    modalContent.addEventListener("animationend", () => {
      modal.classList.remove("active");
    }, { once: true });
}


const createResponseElement = () => {
    let responseMessage = document.getElementById('responseMessage');
    if (!responseMessage) {
        responseMessage = document.createElement('div');
        responseMessage.id = 'responseMessage';
        // Add it after the form
        const form = document.getElementById('addVehicleForm');
        form.parentNode.insertBefore(responseMessage, form.nextSibling);
    }
    return responseMessage;
};

document.getElementById('addVehicleForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Create or get response message element
    const responseMessage = createResponseElement();
    responseMessage.textContent = 'Uploading...';
    responseMessage.className = '';
    
    const formData = new FormData(this);
    
    // Log the FormData contents for debugging (optional)
    for (let pair of formData.entries()) {
        console.log(`${pair[0]}: ${pair[1]}`);
    }

    fetch('add_vehicle.php', {
        method: 'POST',
        body: formData
    })
        
    .then(response => response.text()) // Fetch raw text for debugging
    .then(text => {
        console.log('Raw server response:', text); // Debug log

        // Parse JSON and handle HTML or other invalid responses
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            // Provide a clear message if the response isn’t valid JSON
            throw new Error(`Server error: ${text.replace(/<[^>]*>/g, '')}`);
        }
        
        // Check if the server returned an error status
        if (!data.status || data.status !== 'success') {
            throw new Error(data.message || 'Server error occurred');
        }

        // Success handling
        responseMessage.classList.remove('text-red-600');
        responseMessage.textContent = data.message;
        responseMessage.classList.add('text-green-600');
        
        // Close modal and refresh vehicle list if functions exist
        if (typeof closeModal === 'function') {
            closeModal();
        }
        if (typeof addVehicleToTable === 'function') {
            addVehicleToTable(data.vehicle); // Add vehicle directly to the table
        }

    })
    .catch(error => {
        console.error('Error:', error);
        responseMessage.textContent = error.message || 'An error occurred while uploading. Please try again.';
        responseMessage.classList.remove('text-green-600');
        responseMessage.classList.add('text-red-600');
    });
});

function addVehicleToTable(vehicle) {
    const tbody = document.querySelector("table tbody");
    const newRow = document.createElement("tr");

    newRow.innerHTML = `
        <td class="p-4 border-b">${vehicle.reg_no}</td>
        <td class="p-4 border-b">${vehicle.type}</td>
        <td class="p-4 border-b">${vehicle.make}</td>
        <td class="p-4 border-b">${vehicle.location}</td>
        <td class="p-4 border-b">
            ${vehicle.status === 'Fixed' ? '<span class="text-green-500 font-bold">✔ Fixed</span>' : '<span class="text-yellow-600 font-bold">⚠ Needs Repairs</span>'}
        </td>
        <td class="p-4 border-b">${vehicle.inspection_date}</td>
        <td class="p-4 border-b flex items-center justify-around space-x-2 text-lg">
            <button onclick="showDetails(${vehicle.id})" class="text-blue-500 hover:text-blue-700">ℹ</button>
            <button onclick="editVehicle(${vehicle.id})" class="text-yellow-500 hover:text-yellow-700">✏</button>
            <a href="clear_vehicle.php?id=${vehicle.id}" class="text-green-500 hover:text-green-700">✔ Clear</a>
            <a href="delete_vehicle.php?id=${vehicle.id}" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this vehicle?')">🗑</a>
        </td>
    `;
    tbody.appendChild(newRow); // Append the new row to the table body
}

// Edit Vehicle Model
function editVehicle(vehicleId) {
    console.log("Opening edit modal for vehicle ID:", vehicleId);

    // Open the edit modal
    const modal = document.getElementById("EditvehicleModal");
    const imageGallery = document.getElementById("imagePreview");
    modal.classList.add("active");

    // Clear form fields to avoid showing old data and set the vehicleId immediately
    document.getElementById("reg_no").value = "";
    document.getElementById("type").value = "";
    document.getElementById("make").value = "";
    document.getElementById("location").value = "";
    document.getElementById("inspection_date").value = "";
    document.getElementById("repair_completion_date").value = "";
    document.getElementById("vehicleId").value = "";

    // Fetch data for the specific vehicle ID
    fetch(`get_vehicle_details.php?id=${vehicleId}`)
        .then(response => response.json())
        .then(vehicle => {
            console.log("Received vehicle data:", vehicle);

            // Update form fields with the fetched vehicle data
            document.getElementById("reg_no").value = vehicle.reg_no;
            document.getElementById("type").value = vehicle.type;
            document.getElementById("make").value = vehicle.make;
            document.getElementById("location").value = vehicle.location;
            document.getElementById("inspection_date").value = vehicle.inspection_date;
            document.getElementById("repair_completion_date").value = vehicle.repair_completion_date;
            document.getElementById("vehicleId").value = vehicle.id;

            // Check if images exist and display them
            const imagesArray = vehicle.images ? vehicle.images.split(',') : []; // Ensure images are split by comma

            imageGallery.innerHTML = '';  // Clear existing images
            imagesArray.forEach(image => {
                // Create an image element for each image in the array
                const imgElement = document.createElement("img");
                imgElement.src = `../assets/vehicles/${image}`;
                imgElement.alt = image;
                imgElement.classList.add("cursor-pointer", "rounded", "shadow-lg", "w-20", "h-20");

                // Append the image element to the image preview section
                imageGallery.appendChild(imgElement);

                // Add a delete button next to the image
                const deleteBtn = document.createElement("button");
                deleteBtn.classList.add("text-red-500", "ml-2");
                deleteBtn.innerHTML = "Delete";
                deleteBtn.onclick = function() {
                    deleteImage(vehicle.id, image); // Call a delete function when clicked
                };
                imageGallery.appendChild(deleteBtn);
            });
        })
        .catch(error => console.error('Error loading vehicle data:', error));
}

function deleteImage(vehicleId, image) {
    if (confirm("Are you sure you want to delete this image?")) {
        fetch(`delete_image.php?vehicle_id=${vehicleId}&image=${image}`, { method: 'GET' })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Remove the image from the display
                    alert("Image deleted successfully!");
                    editVehicle(vehicleId);  // Reload the modal to refresh images
                } else {
                    alert("Failed to delete the image.");
                }
            })
            .catch(error => console.error('Error deleting image:', error));
    }
}


function closeEditModal(){
    console.log("Closing Edit Modal");
    document.getElementById("EditvehicleModal").classList.remove("active");
    document.getElementById("EditvehicleModal").classList.add("hide");
}

function submitEditForm() {
    const formData = new FormData(document.getElementById("editVehicleForm"));
    fetch("edit_vehicle.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("Update successful")) {
            closeEditModal();
        } else {
            console.error("Update failed:", data);
        }
    })
    .catch(error => console.error("Error submitting form:", error));
}


// Show Vehicle Details Model
function showDetails(vehicleId) {
    const detailsModal = document.getElementById("detailsModal");
    const detailsModalContent = document.getElementById("detailsModalContent");

    // Remove "hide" class to trigger slide-up animation
    detailsModalContent.classList.remove("hide");
    detailsModal.classList.add("active"); // Show modal overlay

    fetch(`get_vehicle_details.php?id=${vehicleId}`)
        .then(response => response.json())
        .then(data => {

            // const detailsModal = document.getElementById("detailsModal");
            const imageGallery = document.getElementById("imageGallery");

            // Populate vehicle details
            document.getElementById("detailRegNo").textContent = data.reg_no || "N/A";
            document.getElementById("detailType").textContent = data.type || "N/A";
            document.getElementById("detailMake").textContent = data.make || "N/A";
            document.getElementById("detailLocation").textContent = data.location || "N/A";
            document.getElementById("detailStatus").textContent = data.status || "Needs Repairs";
            document.getElementById("detailRepair").textContent = data.repair_type || data.status;
            document.getElementById("detailInspectionDate").textContent = data.inspection_date || "N/A";
  

            // Check if images exist and convert to an array if it's a comma-separated string
            const imagesArray = typeof data.images === 'string' ? data.images.split(',') : [];

            imageGallery.innerHTML = ''; // Clear existing content
            images.length = 0; // Clear previous images
            images.push(...imagesArray); // Populate the global images array for carousel navigation

            // Populate image gallery with thumbnails
            imagesArray.forEach((image, index) => {
                const imgElement = document.createElement("img");
                imgElement.src = `../assets/vehicles/${image}`;
                imgElement.classList.add("cursor-pointer", "rounded", "shadow-lg");
                imgElement.onclick = () => openCarousel(index);
                imageGallery.appendChild(imgElement);
            });

            detailsModal.classList.remove("hidden");
        })
        .catch(error => {
            alert("Failed to load vehicle details");
            console.error(error);
        });
}


function closeDetailsModal() {
    const detailsModal = document.getElementById("detailsModal");
    const detailsModalContent = document.getElementById("detailsModalContent");

    detailsModalContent.classList.add("hide");  

    setTimeout(() => {
        detailsModal.classList.remove("active"); 
    }, 400);
}



function previewImages() {
    const imagePreview = document.getElementById('imagePreview');
    const files = document.getElementById('images').files;

    imagePreview.innerHTML = ''; // Clear previous previews

    for (const file of files) {
        const reader = new FileReader();
        
        reader.onload = function(event) {
            const imgElement = document.createElement('img');
            imgElement.src = event.target.result;
            imgElement.classList.add('w-40', 'h-40', 'object-cover', 'rounded');
            imagePreview.appendChild(imgElement);
        };

        reader.readAsDataURL(file);
    }

    console.log("Preview function called");
}



const images = []; // Fill this array with image paths
let currentIndex = 0;

function showdetails() {
    document.getElementById("detailsModal").classList.remove("hidden");
}

function closeDetails() {
    document.getElementById("detailsModal").classList.add("hidden");
    closeCarousel(); // Close the carousel if open
}

// Dynamically populate thumbnails
function populateGallery() {
    const gallery = document.getElementById("imageGallery");
    gallery.innerHTML = '';
    images.forEach((src, index) => {
        const img = document.createElement("img");
        img.src = src;
        img.classList.add("cursor-pointer", "object-cover", "w-full", "h-24", "rounded");
        img.onclick = () => openCarousel(index);
        gallery.appendChild(img);
    });
}

// Carousel open, close, and navigation functions
function openCarousel() {
    const carousel = document.getElementById('carouselModal');
    const carouselContent = document.querySelector('.carousel-content');
    carousel.classList.remove('hidden');
    carousel.classList.add('active');
    setTimeout(() => carouselContent.classList.add('active'), 10);
}

function closeCarousel() {
    const carousel = document.getElementById('carouselModal');
    const carouselContent = document.querySelector('.carousel-content');
    carouselContent.classList.add('hidden');
    carouselContent.classList.remove('active'); 
    setTimeout(() => carousel.classList.remove('active'), 300);
}

document.getElementById('carouselModal').addEventListener('click', closeCarousel);

function updateCarouselImage() {
    const enlargedImg = document.getElementById("enlargedImg");
    enlargedImg.src = `../assets/vehicles/${images[currentIndex]}`;
}

function showPrevImage() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateCarouselImage();
}

function showNextImage() {
    currentIndex = (currentIndex + 1) % images.length;
    updateCarouselImage();
}

// Call this function when you open the modal to load the thumbnails
populateGallery();