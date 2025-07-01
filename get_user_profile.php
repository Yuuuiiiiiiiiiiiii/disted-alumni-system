<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "alumni_management_system";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // User ID from session

// Check if a profile picture is uploaded and if there's no error
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Allowed file types
    $fileName = $_FILES['profile_picture']['name'];
    $fileTmpName = $_FILES['profile_picture']['tmp_name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Check if the file extension is allowed
    if (in_array($fileExtension, $allowedExtensions)) {
        // Set the upload directory
        $uploadDir = 'uploads/';
        
        // Check if the upload directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique file name to avoid collisions
        $newFileName = uniqid('', true) . '.' . $fileExtension;
        $targetPath = $uploadDir . $newFileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($fileTmpName, $targetPath)) {
            // Prepare the SQL query to update the profile picture in the database
            $sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $newFileName, $user_id); // Bind parameters

            // Execute the query and check for success
            if ($stmt->execute()) {
                // Return the new image path to be displayed in the frontend
                echo json_encode([
                    'success' => true, 
                    'profile_picture' => $targetPath // Send the URL of the uploaded image
                ]);
            } else {
                echo json_encode(['error' => 'Database update failed']);
            }
        } else {
            echo json_encode(['error' => 'File upload failed']);
        }
    } else {
        echo json_encode(['error' => 'Invalid file type']);
    }
} else {
    echo json_encode(['error' => 'No file uploaded or file upload error']);
}

// Close the database connection
$conn->close();
?>