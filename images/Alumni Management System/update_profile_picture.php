<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "alumni_management_system";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; 

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; 
    $fileName = $_FILES['profile_picture']['name'];
    $fileTmpName = $_FILES['profile_picture']['tmp_name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $fileSize = $_FILES['profile_picture']['size']; 

    $maxFileSize = 2 * 1024 * 1024;
    if ($fileSize > $maxFileSize) {
        echo json_encode(['error' => 'File is too large']);
        exit();
    }

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['error' => 'Invalid file type']);
        exit();
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $fileTmpName);
    finfo_close($finfo);
    
    $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mimeType, $validMimeTypes)) {
        echo json_encode(['error' => 'Invalid file type']);
        exit();
    }

    $uploadDir = 'uploads';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $newFileName = uniqid('', true) . '.' . $fileExtension;
    $targetPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpName, $targetPath)) {
        $sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $targetPath, $user_id);

        if ($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'profile_picture' => $targetPath
            ]);
        } else {
            echo json_encode(['error' => 'Database update failed']);
        }
    } else {
        echo json_encode(['error' => 'File upload failed']);
    }
} else {
    echo json_encode(['error' => 'No file uploaded or file upload error']);
}

$conn->close();
?>