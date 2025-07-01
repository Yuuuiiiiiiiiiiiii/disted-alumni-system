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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $upload_dir = "uploads/";  
    $target_file = $upload_dir . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        echo json_encode(['error' => 'File is not an image']);
        exit();
    }

    if (file_exists($target_file)) {
        echo json_encode(['error' => 'Sorry, file already exists.']);
        exit();
    }

    if ($_FILES["profile_picture"]["size"] > 5000000) {
        echo json_encode(['error' => 'Sorry, your file is too large.']);
        exit();
    }

    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo json_encode(['error' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.']);
        exit();
    }

    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $file_path = $upload_dir . basename($_FILES["profile_picture"]["name"]);
        $sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $file_path, $user_id);
        $stmt->execute();

        echo json_encode([
            'success' => 'Profile picture updated successfully',
            'profile_picture' => $file_path
        ]);
    } else {
        echo json_encode(['error' => 'Sorry, there was an error uploading your file.']);
    }

    $conn->close();
    exit();
}

$sql = "SELECT name_as_per_ic, username, email, age, gender, phone_number, address, student_id, subject_code, enrollment_date, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name_as_per_ic, $username, $email, $age, $gender, $phone_number, $address, $student_id, $subject_code, $enrollment_date, $profile_picture);

if ($stmt->fetch()) {
    echo json_encode([
        'name_as_per_ic' => $name_as_per_ic,
        'username' => $username,
        'email' => $email,
        'age' => $age,
        'gender' => $gender,
        'phone_number' => $phone_number,
        'address' => $address,
        'student_id' => $student_id,
        'subject_code' => $subject_code,
        'enrollment_date' => $enrollment_date,
        'profile_picture' => $profile_picture 
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}

$conn->close();
?>