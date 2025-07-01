<!doctype html>
<html>
<head>
<link rel="stylesheet" href="Final Project CSS Designs.css">
<link rel="stylesheet" href="SwiperSlide.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<meta charset="utf-8">
<title>DISTED Alumni Management System</title>
<link rel="stylesheet" href="donation.css">
</head>
<body>
<header>
	<section class="header-container">
        <a href="Main Page.html"><img src="Disted Logo(1).jpg" alt="Disted Logo" class="logo" ></a>
        <a href="PROFILE (2).html"><img class="imgcontainer" src="user.png" alt="Avatar" ></a>
	</section>
<p class="p1">Main Site </p>
<div class="button-container_header">
	<button class="button-design_header" onClick="toAAndSHighlights()">Student Highlights</button>
	<button class="button-design_header" onclick="toMAndJPostings()">Mentorship & Jobs</button>
	<button class="button-design_header">Communication</button>
  <button class="button-design_header" onclick="toDonation()">Donation</button>
	<button class="button-design_header">Feedback</button>
</div>
</header>

<body>
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "alumni_management_system";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$campaignId    = isset($_POST['campaign_id'])    ? intval($_POST['campaign_id']) : 0;
$donorName     = isset($_POST['donorName'])      ? trim($_POST['donorName'])     : '';
$donorEmail    = isset($_POST['donorEmail'])     ? trim($_POST['donorEmail'])    : '';
$donorContact  = isset($_POST['donorContact'])   ? trim($_POST['donorContact'])  : '';
$donationAmount= isset($_POST['donationAmount']) ? floatval($_POST['donationAmount']) : 0;
$donationMsg   = isset($_POST['donationMessage'])? trim($_POST['donationMessage']): '';

$errors = [];
if ($campaignId <= 0) {
    $errors[] = "Invalid campaign.";
}
if ($donorName === '') {
    $errors[] = "Name is required.";
}
if (!filter_var($donorEmail, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email is required.";
}
if (strlen($donorContact) < 7) {
    $errors[] = "Valid contact number is required.";
}
if ($donationAmount <= 0) {
    $errors[] = "Donation amount must be greater than 0.";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p>Error: $error</p>";
    }
    exit;
}

$stmt = $conn->prepare("SELECT donor_id FROM donors WHERE email = ?");
$stmt->bind_param("s", $donorEmail);
$stmt->execute();
$stmt->bind_result($existingDonorId);
$stmt->fetch();
$stmt->close();

if ($existingDonorId) {
    $donorId = $existingDonorId;
} else {
    // New donor—insert into donors table
    $stmt = $conn->prepare("INSERT INTO donors (name, email, contact_number) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $donorName, $donorEmail, $donorContact);
    $stmt->execute();
    $donorId = $stmt->insert_id;
    $stmt->close();
}

// 5. Insert donation record
$receiptId = "RCPT" . time() . rand(100, 999); // Example: RCPT1625601234567
$stmt = $conn->prepare("
    INSERT INTO donations (campaign_id, donor_id, amount, message, receipt_id)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("iidsi", $campaignId, $donorId, $donationAmount, $donationMsg, $receiptId);
$stmt->execute();
$donationId = $stmt->insert_id;
$stmt->close();

// 6. Update campaign's current_amount
$stmt = $conn->prepare("
    UPDATE campaigns 
    SET current_amount = current_amount + ? 
    WHERE campaign_id = ?
");
$stmt->bind_param("di", $donationAmount, $campaignId);
$stmt->execute();
$stmt->close();

// 7. Redirect to success page with query parameters
header("Location: donation_success.php?donation_id=$donationId&receipt_id=$receiptId");
exit;

// 8. Close connection (never reached due to exit)
$conn->close();
?>

</body>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<div id="footer-placeholder"></div>
<script src="Button Scripts.js"></script>
</body>
</html>