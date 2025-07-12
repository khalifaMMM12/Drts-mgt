function openLogoutModal() {
    const logoutModal = document.getElementById('logoutModal');
    const logoutModalcontent = document.getElementById('logoutModalcontent');
    if (logoutModal && logoutModalcontent) {
        logoutModal.classList.add("active");
        logoutModalcontent.classList.remove("hide");
    }
}

function closeLogoutModal() {
    const logoutModal = document.getElementById('logoutModal');
    const logoutModalcontent = document.getElementById('logoutModalcontent');
    if (logoutModal && logoutModalcontent) {
        logoutModalcontent.classList.add("hide");
        logoutModalcontent.addEventListener('animationend', () => {
            logoutModal.classList.remove("active");
        }, { once: true });
    }
}

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const openSidebarButton = document.getElementById('open-sidebar');
    if (openSidebarButton && sidebar) {
        openSidebarButton.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
        openSidebarButton.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('-translate-x-full');
        });
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !openSidebarButton.contains(e.target)) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.add('sidebar-close');
            }
        });
    }
});

function hasPermission(permission) {
    if (window.isAdmin === true) {
        return true;
    }
    if (typeof window.userPermissions === 'object' && window.userPermissions !== null) {
        return Boolean(window.userPermissions[permission]);
    }
    return false;
}

function openModal() {
    const modal = document.getElementById("vehicleModal");
    const modalContent = document.getElementById("vehicleModalContent");
    if (!hasPermission('add_vehicle')) {
        showAlert('You do not have permission to add vehicles');
        return;
    }
    if (modal && modalContent) {
        modal.classList.add("active");
        modalContent.classList.remove("hide");
    }
}

function closeModal() {
    const modal = document.getElementById("vehicleModal");
    const modalContent = document.getElementById("vehicleModalContent");
    if (modal && modalContent) {
        modalContent.classList.add("hide");
        modalContent.addEventListener("animationend", () => {
            modal.classList.remove("active");
        }, { once: true });
    }
}

function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    alertDiv.textContent = message;
    document.body.appendChild(alertDiv);
    setTimeout(() => { alertDiv.remove(); }, 3000);
}

const createResponseElement = () => {
    let responseMessage = document.getElementById('responseMessage');
    if (!responseMessage) {
        responseMessage = document.createElement('div');
        responseMessage.id = 'responseMessage';
        const form = document.getElementById('addVehicleForm');
        if (form) form.parentNode.insertBefore(responseMessage, form.nextSibling);
    }
    return responseMessage;
};

const addVehicleForm = document.getElementById('addVehicleForm');
if (addVehicleForm) {
    addVehicleForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const responseMessage = createResponseElement();
        responseMessage.textContent = 'Uploading...';
        responseMessage.className = '';
        const formData = new FormData(this);
        fetch('add_vehicle.php', {
            method: 'POST',
            body: formData
        })
        .then((response) => response.text())
        .then((text) => {
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                throw new Error(`Server response was not valid JSON: ${text.replace(/<[^>]*>/g, '')}`);
            }
            if (!data.status || data.status !== 'success') {
                throw new Error(data.message || 'Server error occurred');
            }
            responseMessage.classList.remove('text-red-600');
            responseMessage.textContent = data.message;
            responseMessage.classList.add('text-green-600');
            if (typeof closeModal === 'function') {
                closeModal();
                this.reset();
                clearImagePreviews();
                responseMessage.textContent = '';
            }
            showAlert('Vehicle Added successfully', 'success');
        })
        .catch(error => {
            responseMessage.textContent = error.message || 'An error occurred while uploading. Please try again.';
            responseMessage.classList.remove('text-green-600');
            responseMessage.classList.add('text-red-600');
        });
    });
}

function clearImagePreviews() {
    const imagePreview = document.getElementById('imagePreview');
    if (imagePreview) imagePreview.innerHTML = '';
    const imageInput = document.getElementById('images');
    if (imageInput) imageInput.value = '';
}

function formatText(text, type = 'normal') {
    if (!text) return '';
    if (type === 'reg') return text.toUpperCase();
    return text.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
}

function showDetails(vehicleId) {
    const detailsModal = document.getElementById("detailsModal");
    const detailsModalContent = document.getElementById("detailsModalContent");
    fetch(`get_vehicle_details.php?id=${vehicleId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(vehicle => {
            document.getElementById("detailRegNo").textContent = vehicle.reg_no || 'N/A';
            document.getElementById("detailType").textContent = vehicle.type || 'N/A';
            document.getElementById("detailMake").textContent = vehicle.make || 'N/A';
            document.getElementById("detailLocation").textContent = vehicle.location || 'N/A';
            document.getElementById("detailInspectionDate").textContent = vehicle.inspection_date || 'N/A';
            // Images
            const imageGallery = document.getElementById("imageGallery");
            imageGallery.innerHTML = '';
            if (vehicle.images) {
                const imagesArray = typeof vehicle.images === 'string' 
                    ? vehicle.images.split(',').filter(img => img.trim())
                    : Array.isArray(vehicle.images) ? vehicle.images : [];
                if (imagesArray.length > 0) {
                    imagesArray.forEach((image, index) => {
                        const imgContainer = document.createElement("div");
                        imgContainer.className = "relative group cursor-pointer hover:opacity-75 transition-opacity";
                        const img = document.createElement("img");
                        img.src = `../assets/vehicles/${image.trim()}`;
                        img.className = "w-full h-32 object-cover rounded-lg shadow-md";
                        img.alt = `Vehicle image ${index + 1}`;
                        imgContainer.onclick = () => { openCarousel(image.trim(), imagesArray); };
                        imgContainer.appendChild(img);
                        imageGallery.appendChild(imgContainer);
                    });
                    currentImages = imagesArray;
                }
            }
            if (detailsModal && detailsModalContent) {
                detailsModal.classList.remove("hidden");
                detailsModal.classList.add("active");
                detailsModalContent.classList.remove("hide");
            }
        })
        .catch(error => {
            showAlert('Error loading vehicle details', 'error');
        });
}

function closeDetailsModal() {
    const detailsModal = document.getElementById("detailsModal");
    const detailsModalContent = document.getElementById("detailsModalContent");
    if (detailsModal && detailsModalContent) {
        detailsModalContent.classList.add("hide");  
        setTimeout(() => { detailsModal.classList.remove("active"); }, 400);
        closeCarousel();
    }
}

function previewImages() {
    const imagePreview = document.getElementById('imagePreview');
    const input = document.getElementById('images');
    if (!input || !imagePreview) return;
    const files = input.files;
    const maxImages = 2;
    imagePreview.innerHTML = '';
    if (files.length > maxImages) {
        showAlert(`Maximum ${maxImages} images allowed`, 'error');
        input.value = '';
        return;
    }
    const processedFiles = new Set();
    Array.from(files).forEach((file, index) => {
        if (processedFiles.has(file.name)) return;
        processedFiles.add(file.name);
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewWrapper = document.createElement('div');
            previewWrapper.className = 'relative group';
            previewWrapper.dataset.fileName = file.name;
            const imgElement = document.createElement('img');
            imgElement.src = e.target.result;
            imgElement.className = 'previewImg object-cover rounded-lg shadow-lg';
            const deleteButton = document.createElement('button');
            deleteButton.className = 'text-lg absolute block top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center';
            deleteButton.innerHTML = 'X';
            deleteButton.onclick = (e) => {
                e.preventDefault();
                removeImage(index, previewWrapper, input);
            };
            previewWrapper.appendChild(imgElement);
            previewWrapper.appendChild(deleteButton);
            imagePreview.appendChild(previewWrapper);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index, previewWrapper, input) {
    if (!input || !input.files) return;
    const dt = new DataTransfer();
    const { files } = input;
    for (let i = 0; i < files.length; i++) {
        if (i !== index) dt.items.add(files[i]);
    }
    input.files = dt.files;
    previewWrapper.remove();
}

// Carousel and modal event listeners
let currentImages = [];
let currentImageIndex = 0;
function initializeCarousel(images) {
    currentImages = images;
    currentImageIndex = 0;
    updateCarouselImage();
    updateImageCounter();
}
function updateCarouselImage() {
    const img = document.getElementById('enlargedImg');
    if (img && currentImages[currentImageIndex]) {
        img.src = `../assets/vehicles/${currentImages[currentImageIndex].trim()}`;
    }
}
function updateImageCounter() {
    const idx = document.getElementById('currentImageIndex');
    const total = document.getElementById('totalImages');
    if (idx && total) {
        idx.textContent = currentImageIndex + 1;
        total.textContent = currentImages.length;
    }
}
function showPrevImage() {
    if (currentImageIndex > 0) {
        currentImageIndex--;
    } else {
        currentImageIndex = currentImages.length - 1;
    }
    updateCarouselImage();
    updateImageCounter();
}
function showNextImage() {
    if (currentImageIndex < currentImages.length - 1) {
        currentImageIndex++;
    } else {
        currentImageIndex = 0;
    }
    updateCarouselImage();
    updateImageCounter();
}
function openCarousel(imageSrc, images) {
    const modal = document.getElementById('carouselModal');
    const enlargedImg = document.getElementById('enlargedImg');
    if (!modal || !enlargedImg) return;
    enlargedImg.style.transform = 'scale(1)';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    const cleanedImages = images.map(img => img.trim());
    initializeCarousel(cleanedImages);
    currentImageIndex = cleanedImages.indexOf(imageSrc.trim());
    updateCarouselImage();
    updateImageCounter();
    document.addEventListener('keydown', handleCarouselKeyPress);
}
function closeCarousel() {
    const modal = document.getElementById('carouselModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    document.removeEventListener('keydown', handleCarouselKeyPress);
}
function handleCarouselKeyPress(e) {
    switch(e.key) {
        case 'ArrowLeft': showPrevImage(); break;
        case 'ArrowRight': showNextImage(); break;
        case 'Escape': closeCarousel(); break;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const detailsModal = document.getElementById("detailsModal");
    const editModal = document.getElementById("EditvehicleModal");
    const vehicleModal = document.getElementById("vehicleModal");
    const deleteModal = document.getElementById("deleteModal");
    const logoutModal = document.getElementById("logoutModal");
    if (logoutModal) {
        logoutModal.addEventListener('click', (e) => {
            if(e.target === logoutModal){ closeLogoutModal(); }
        });
    }
    if (deleteModal) {
        deleteModal.addEventListener('click', (e) => {
            if(e.target === deleteModal){ closeDeleteModal(); }
        });
    }
    if (vehicleModal) {
        vehicleModal.addEventListener('click', (e) => {
            if (e.target === vehicleModal) { closeModal(); }
        });
    }
    if (editModal) {
        editModal.addEventListener('click', (e) => {
            if (e.target === editModal) { closeEditModal(); }
        });
    }
    if (detailsModal) {
        detailsModal.addEventListener('click', (e) => {
            if (e.target === detailsModal) { closeDetailsModal(); }
        });
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && detailsModal && detailsModal.classList.contains('active')) {
            closeDetailsModal();
        }
    });
});
