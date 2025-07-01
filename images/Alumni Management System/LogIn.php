<?php
session_start();  

$servername = "localhost";
$username = "root"; 
$password = "";  
$dbname = "alumni_management_system"; 

date_default_timezone_set('Asia/Kuala_Lumpur'); 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("SET time_zone = '+08:00'"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; 
    $password = $_POST['password']; 

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); 

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username'];  

            $currentDate = date('Y-m-d H:i:s');
            
            $updateSql = "UPDATE users SET last_login = ? WHERE username = ?";
            $updateStmt = $conn->prepare($updateSql);
            if ($updateStmt) {
                $updateStmt->bind_param("ss", $currentDate, $username);
                $updateStmt->execute();
                if ($updateStmt->affected_rows > 0) {
                } else {
                    echo "Failed to update last login time.<br>"; 
                }
                $updateStmt->close();
            }

            $checkSql = "SELECT last_login FROM users WHERE username = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("s", $username);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $checkRow = $checkResult->fetch_assoc();

            $lastLoginTime = $checkRow['last_login'];

            header("Location: Main Page.html");
            exit(); 
        } else {
            echo "<script>alert('Incorrect password.'); window.location.href='Alumni Management Page.html';</script>";
        }
    } else {
        echo "<script>alert('Username not found.'); window.location.href='Alumni Management Page.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>