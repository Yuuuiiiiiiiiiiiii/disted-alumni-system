<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "alumni_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_as_per_ic = $conn->real_escape_string($_POST['name_as_per_ic']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; 
    $age = $_POST['age']; 
    $gender = $_POST['gender'];
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $_POST['phone_number'];
    $address = $conn->real_escape_string($_POST['address']);
    $subject_code = $_POST['subject_code']; 
    $enrollment_year = date('y');  

    $sql_count = "SELECT COUNT(*) AS total FROM users WHERE subject_code = '$subject_code' AND YEAR(enrollment_date) = YEAR(CURDATE())";
    $result = $conn->query($sql_count);
    $row = $result->fetch_assoc();
    $sequential_number = str_pad($row['total'] + 1, 3, '0', STR_PAD_LEFT); 
    
    $student_id = $subject_code . $enrollment_year . $sequential_number;

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        echo "Username or email already exists!";
    } else {
        $sql = "INSERT INTO users (name_as_per_ic, username, password, age, gender, email, phone_number, address, student_id, subject_code, enrollment_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssssssssss", $name_as_per_ic, $username, $hashed_password, $age, $gender, $email, $phone_number, $address, $student_id, $subject_code);

        if ($stmt->execute()) {
            header("Location: confirmation.php?student_id=" . urlencode($student_id));
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

$conn->close();
?>