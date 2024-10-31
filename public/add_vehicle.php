<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $reg_no = $_POST['reg_no'];
    $type = $_POST['type'];
    $make = $_POST['make'];
    $location = $_POST['location'];
    $inspection_date = $_POST['inspection_date'];

    // Initialize an array to hold the filenames
    $imageNames = [];

    // Handle multiple file assets
    if (isset($_FILES['images'])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetDir = "../assets/"; // Ensure this directory exists
            $targetFilePath = $targetDir . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($tmp_name, $targetFilePath)) {
                $imageNames[] = $fileName; // Store the file name in the array
            }
        }
    }

    // Convert the array of image names to a comma-separated string
    $imagesString = implode(',', $imageNames);

    // Insert vehicle data into the database
    $sql = "INSERT INTO vehicles (reg_no, type, make, location, inspection_date, images) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    // Check if insertion is successful
    if ($stmt->execute([$reg_no, $type, $make, $location, $inspection_date, $imagesString])) {
        $response = ['status' => 'success', 'message' => 'Vehicle added successfully'];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to add vehicle'];
    }

    // Output JSON response
    echo json_encode($response);
    exit();
}
?>
