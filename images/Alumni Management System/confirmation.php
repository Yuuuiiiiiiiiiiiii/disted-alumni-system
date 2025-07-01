<?php
if (isset($_GET['student_id'])) {
    $student_id = urldecode($_GET['student_id']);
} else {
    header("Location: SignUp.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sign Up Confirmation</title>
    <style>
        #studentIdModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            text-align: center;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            float: right;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div id="studentIdModal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Congratulations!</h2>
        <p>Your Student ID is: <strong><?php echo $student_id; ?></strong></p>
        <p>Please keep this ID for future reference.</p>
    </div>
</div>

<script>
    window.onload = function() {
        document.getElementById("studentIdModal").style.display = "block";
    };

    function closeModal() {
        document.getElementById("studentIdModal").style.display = "none";
        window.location.href = "Alumni Management Page.html";
    }
</script>

</body>
</html>