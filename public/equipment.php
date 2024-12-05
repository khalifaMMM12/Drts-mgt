<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Office Equipment Status</title>
</head>
<body class="bg-gray-100 mt-8">
    <div class="container mx-auto p-4 md:p-6 lg:px-8">
        <h1 class="text-3xl font-bold text-yellow-500 mb-6">Office Equipments Status</h1>

        <!-- Dropdown for Selecting Equipment -->
        <div class="flex flex-col md:flex-row items-center w-full gap-4 mb-6">
            <label for="equipmentSelect" class="block text-lg font-medium text-gray-700 mb-2">Select Equipment:</label>
            <select id="equipmentSelect" class="p-4 border-b border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <option value="solar">Solar</option>
                <option value="airConditioners">Air Conditioners</option>
                <option value="fireExtinguishers">Fire Extinguishers</option>
            </select>

            <!-- Add Equipment Button -->
            <button id="addEquipmentButton" class="rounded bg-gradient-to-b from-yellow-500 to-yellow-600 hover:to-yellow-700 text-white px-4 py-2 shadow-lg">
                Add Equipment
            </button>
            <a href="index.php" class="rounded bg-gradient-to-b from-yellow-500 to-yellow-600 hover:to-yellow-700 text-white px-4 py-2 shadow-lg">
                Vehicles
            </a>
        </div>


        <!-- Table for Displaying Equipment Data -->
        <div id="equipmentTableContainer" class="overflow-x-auto">
            <table id="solarTable" class="w-full bg-white shadow-lg rounded overflow-hidden text-sm md:text-base">
                <thead class="bg-yellow-500 text-black">
                    <tr>
                        <th class="p-4 border-b">Location</th>
                        <th class="p-4 border-b">Capacity</th>
                        <th class="p-4 border-b">Battery Type</th>
                        <th class="p-4 border-b">No. of Batteries</th>
                        <th class="p-4 border-b">No. of Panels</th>
                        <th class="p-4 border-b">Date Added</th>
                        <th class="p-4 border-b">Action</th>
                        <!-- <th class="p-4 border-b">Service Rendered</th> -->
                    </tr>
                </thead>
                <tbody id="solarData"></tbody>
            </table>

            <table id="airConditionersTable" class="w-full bg-white shadow-lg rounded overflow-hidden text-sm md:text-base hidden">
                <thead class="bg-yellow-500 text-black">
                    <tr>
                        <th class="p-4 border-b">Location</th>
                        <th class="p-4 border-b">Model</th>
                        <th class="p-4 border-b">Type</th>
                        <th class="p-4 border-b">No. of Units</th>
                        <th class="p-4 border-b">Capacity</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody id="airConditionersData"></tbody>
            </table>

            <table id="fireExtinguishersTable" class="w-full bg-white shadow-lg rounded overflow-hidden text-sm md:text-base hidden">
                <thead class="bg-yellow-500 text-black">
                    <tr>
                        <th class="p-4 border-b">Type</th>
                        <th class="p-4 border-b">Weight</th>
                        <th class="p-4 border-b">Amount</th>
                        <th class="p-4 border-b">Location</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b">Last Service Date</th>
                        <th class="p-4 border-b">Expiration Date</th>
                        <th class="p-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody id="fireExtinguishersData"></tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="addEquipmentModal" class="modal-overlay hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4">
        <div class="modal-content relative bg-white p-6 rounded-lg shadow-lg border-2 border-yellow-400 w-full max-w-lg md:max-w-2xl lg:max-w-3xl overflow-y-auto max-h-full">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 text-4xl">&times;</button>
            <h2 id="modalTitle" class="text-xl font-bold mb-4"></h2>
            <form id="addEquipmentForm">
                <input type="hidden" id="equipmentType" name="equipmentType">
                <div id="fields" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Fields input -->
                    
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" id="cancelButton" class="mr-4 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded-lg shadow hover:bg-yellow-600">
                        Add Equipment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../scripts/Equipments.js"></script>
    <script src="https://kit.fontawesome.com/79a49acde1.js" crossorigin="anonymous"></script>
</body>
</html>
