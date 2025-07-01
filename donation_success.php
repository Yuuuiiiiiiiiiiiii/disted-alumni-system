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

$donationId = isset($_GET['donation_id']) ? intval($_GET['donation_id']) : 0;
$receiptId  = isset($_GET['receipt_id'])  ? trim($_GET['receipt_id'])   : 'N/A';

$campaignName = "XXX Campaign";
$donationAmount = 0.00;
$donationDate = '';

if ($donationId > 0) {
    $stmt = $conn->prepare("
        SELECT d.amount, d.date, c.title
        FROM donations d
        JOIN campaigns c ON d.campaign_id = c.campaign_id
        WHERE d.donation_id = ?
    ");
    $stmt->bind_param("i", $donationId);
    $stmt->execute();
    $stmt->bind_result($donationAmount, $donationDate, $campaignName);
    $stmt->fetch();
    $stmt->close();
}
?>

<div class="donation-success-container">
  <div class="success-icon">
    <img src="G1.gif" alt="...">
  </div>
  <h2 class="success-title">Thank You for Your Donation!</h2>
  <p class="success-desc">
    Your generosity is greatly appreciated. Below is a summary of your donation:
  </p>

  <div class="donation-summary">
    <p><strong>Campaign:</strong> <?php echo htmlspecialchars($campaignName); ?></p>
    <p><strong>Amount Donated:</strong> RM <?php echo number_format($donationAmount, 2); ?></p>
    <p><strong>Receipt ID:</strong> <?php echo htmlspecialchars($receiptId); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($donationDate); ?></p>
  </div>

  
  <div class="success-buttons">
    <a href="donation_campaigns.php" class="btn btn-primary">Back to Campaigns</a>
    <a href="donation_history.php" class="btn btn-secondary">View Donation History</a>
  </div>
</div>

<?php
$conn->close();
?>

</body>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<div id="footer-placeholder"></div>
<script src="Button Scripts.js"></script>
</body>
</html>