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
            addVehicleToTable(data.vehicle);
        }
        

    })
    .catch(error => {
        console.error('Error:', error);
        responseMessage.textContent = error.message || 'An error occurred while uploading. Please try again.';
        responseMessage.classList.remove('text-green-600');
        responseMessage.classList.add('text-red-600');
    });
});

function updateRepairStatus() {
    const needsRepairs = document.getElementById("needsRepairs");
    const statusDisplay = document.getElementById("statusDisplay");
    const repairTypeField = document.getElementById("repairTypeField");

    if (needsRepairs.checked) {
        statusDisplay.textContent = "Needs Repairs";
        repairTypeField.classList.remove("hidden");
    } else {
        statusDisplay.textContent = "No Repairs";
        repairTypeField.classList.add("hidden");
    }
}


function addVehicleToTable(vehicle) {
    const tbody = document.querySelector("table tbody");
    const newRow = document.createElement("tr");


    newRow.innerHTML = `
        <td class="p-4 border-b">${vehicle.reg_no}</td>
        <td class="p-4 border-b">${vehicle.type}</td>
        <td class="p-4 border-b">${vehicle.make}</td>
        <td class="p-4 border-b">${vehicle.location}</td>
        <td class="p-4 border-b">${vehicle.status}</td>
        <td class="p-4 border-b">${vehicle.inspection_date}</td>
        <td class="p-4 border-b flex items-center justify-around space-x-2 text-lg">
            <button onclick="showDetails(${vehicle.id})" class="text-blue-500 hover:text-blue-700">ℹ</button>
            <button onclick="editVehicle(${vehicle.id})" class="text-yellow-500 hover:text-yellow-700"><i class="fa-solid fa-pen-to-square"></i></button>
            <a href="clear_vehicle.php?id=${vehicle.id}" class="text-green-500 hover:text-green-700">✔ Clear</a>
            <button class="text-red-500 hover:text-red-700 delete-button" data-vehicle-id="${vehicle.id}" onclick="openDeleteModal(${vehicle.id})"><i class="fa-solid fa-trash-can"></i></button>
        </td>
    `;
    tbody.appendChild(newRow);

}



// Show Vehicle Details Model
// Show details modal
function showDetails(vehicleId) {
    const detailsModal = document.getElementById("detailsModal");
    const detailsModalContent = document.getElementById("detailsModalContent");

    // Remove "hide" class to trigger slide-up animation
    detailsModalContent.classList.remove("hide");
    detailsModal.classList.add("active"); // Show modal overlay

    fetch(`get_vehicle_details.php?id=${vehicleId}`)
        .then(response => response.json())
        .then(data => {
            const imageGallery = document.getElementById("imageGallery");

            // Populate vehicle details
            document.getElementById("detailRegNo").textContent = data.reg_no || "N/A";
            document.getElementById("detailType").textContent = data.type || "N/A";
            document.getElementById("detailMake").textContent = data.make || "N/A";
            document.getElementById("detailLocation").textContent = data.location || "N/A";
            document.getElementById("detailStatus").textContent = data.status;
            document.getElementById("detailRepair").textContent = data.repair_type;
            document.getElementById("detailInspectionDate").textContent = data.inspection_date || "N/A";
            document.getElementById("detailRepairDate").textContent = data.repair_completion_date || "Not fixed";

            // Process images
            const imagesArray = (typeof data.images === 'string' && data.images.trim()) 
                ? data.images.split(',').map(img => img.trim())
                : [];

            imageGallery.innerHTML = ''; // Clear existing content
            images.length = 0; // Clear previous images
            images.push(...imagesArray); // Populate the global images array for carousel navigation

            if (imagesArray.length > 0) {
                imageGallery.style.display = "grid"; // Show gallery if images exist

                // Populate image gallery with thumbnails
                imagesArray.forEach((images, index) => {
                    const imgElement = document.createElement("img");
                    imgElement.src = `../assets/vehicles/${images}`;
                    imgElement.classList.add("cursor-pointer", "rounded", "shadow-lg");
                    imgElement.onclick = () => openCarousel(index); // Open carousel at specific image
                    imageGallery.appendChild(imgElement);
                });
            } else {
                imageGallery.style.display = "none";
            }

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
    console.log("details model closed");
    
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

function showdetails() {
    document.getElementById("detailsModal").classList.remove("hidden");
    console.log("details model");
}

function closeDetails() {
    document.getElementById("detailsModal").classList.add("hidden");
    closeCarousel();
    console.log("details model closed");
}


// Ensure images is an array
let images = []; 
let currentIndex = 0;

function openCarousel(index = 0) {
    if (!Array.isArray(images) || images.length === 0) {
        console.error('No images available');
        return;
    }

    const carousel = document.getElementById('carouselModal');
    carousel.classList.remove('hidden'); 

    // Ensure index is within bounds
    currentIndex = Math.min(Math.max(index, 0), images.length - 1);

    updateCarouselImage(); 
}

function updateCarouselImage() {
    const enlargedImg = document.getElementById("enlargedImg");
    
    if (images[currentIndex]) {
        enlargedImg.src = `../assets/vehicles/${images[currentIndex]}`; 
        enlargedImg.alt = `Vehicle Image ${currentIndex + 1}`;
    } else {
        console.error("Image not found.");
    }
}



function showPrevImage() {
    if (images.length <= 0) return;
    currentIndex = (currentIndex - 1 + images.length) % images.length;  // Loop to previous image
    updateCarouselImage();
}

function showNextImage() {
    if (images.length <= 0) return;
    currentIndex = (currentIndex + 1) % images.length;  // Loop to next image
    updateCarouselImage();
}

function closeCarousel() {
    const carousel = document.getElementById('carouselModal');
    carousel.classList.add('hidden'); 
}

// Attach the image array from the details modal to the carousel
function setCarouselImages(imageArray) {
    images = imageArray;
    currentIndex = 0;  // Reset index to start from the first image
}

// Event Listeners for the Carousel
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('carouselModal').addEventListener('click', (e) => {
        if (e.target.id === 'carouselModal') {
            closeCarousel();
        }
    });

    document.getElementById('closeCarousel').addEventListener('click', closeCarousel);
    document.getElementById('prevImage').addEventListener('click', showPrevImage);
    document.getElementById('nextImage').addEventListener('click', showNextImage);
});
