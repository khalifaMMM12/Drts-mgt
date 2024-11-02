// function openModal() {
//     document.getElementById("vehicleModal").classList.remove("hidden");
// }

// function closeModal() {
//     document.getElementById("vehicleModal").classList.add("hidden");
// }

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
            // const vehicleDetails = document.getElementById("vehicleDetails");

            // if (!detailsModal || !vehicleDetails) {
            //     console.error("Modal or vehicle details container not found in DOM.");
            //     return;
            // }

            vehicleDetails.innerHTML = `
                <p><strong>Registration No:</strong> ${data.reg_no}</p>
                <p><strong>Type:</strong> ${data.type}</p>
                <p><strong>Make:</strong> ${data.make}</p>
                <p><strong>Location:</strong> ${data.location}</p>
                <p><strong>Status:</strong> ${data.status}</p>
                <p><strong>Repair Type:</strong> ${data.repair_type || 'N/A'}</p>
                <p><strong>Inspection Date:</strong> ${data.inspection_date}</p>
                <p><strong>Repair Completion Date:</strong> ${data.repair_completion_date || 'N/A'}</p>
                <img src="../public/assets/${data.picture}" alt="Vehicle Image" class="mt-4 w-full rounded shadow-lg object-cover">
            `;

            detailsModal.classList.remove("hidden");

            // Close modal if clicked outside of it
            detailsModal.addEventListener("click", (event) => {
                if (event.target === detailsModal) {
                    closeDetailsModal();
                }
            });
        })
        .catch(error => {
            alert("Failed to load vehicle details");
            console.error(error);
        });
}

function closeDetailsModal() {
    document.getElementById("detailsModal").classList.add("hidden");
}

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
  
  
    document.getElementById('addVehicleForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('add_vehicle.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const responseMessage = document.getElementById('responseMessage');
        if (data.status === 'success') {
            responseMessage.textContent = data.message;
            responseMessage.classList.add('text-green-600');
            closeModal();
            // Optionally refresh or reload vehicle list
        } else {
            responseMessage.textContent = data.message;
            responseMessage.classList.add('text-red-600');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('responseMessage').textContent = 'An error occurred. Please try again.';
    });
});

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

function openModal() {
    document.getElementById("detailsModal").classList.remove("hidden");
}

function closeModal() {
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
function openCarousel(index) {
    currentIndex = index;
    updateCarouselImage();
    document.getElementById("carouselModal").classList.remove("hidden");
}

function closeCarousel() {
    document.getElementById("carouselModal").classList.add("hidden");
}

function updateCarouselImage() {
    const enlargedImg = document.getElementById("enlargedImg");
    enlargedImg.src = images[currentIndex];
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
