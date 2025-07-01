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
  <button class="button-design_header" onclick="toFeedback()">Feedback</button>
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

$userEmail = isset($_GET['email']) ? trim($_GET['email']) : '';

$donorId = 0;
if ($userEmail !== '') {
    $stmt = $conn->prepare("SELECT donor_id FROM donors WHERE email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $stmt->bind_result($donorId);
    $stmt->fetch();
    $stmt->close();
}

$donations = [];
if ($donorId > 0) {
    $sql = "
        SELECT d.date, c.title AS campaign, d.amount, d.message, d.receipt_id
        FROM donations d
        JOIN campaigns c ON d.campaign_id = c.campaign_id
        WHERE d.donor_id = ?
        ORDER BY d.date DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $donorId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
    $stmt->close();
}
?>

<div class="history-container">
<img src="P6.jpg" alt="...">
  <h2 class="history-title">Your Donation History</h2>

  <?php if ($userEmail === ''): ?>
    <!-- <p class="history-note">Please provide your email address in the URL to view history.</p> -->
    <!-- <p class="history-note">Example: <code>?email=example@example.com</code></p> -->

  <?php elseif ($donorId === 0): ?>
    <p class="no-records">No donation records found for "<?php echo htmlspecialchars($userEmail); ?>".</p>

  <?php elseif (empty($donations)): ?>
    <p class="no-records">You have not made any donations yet.</p>

  <?php else: ?>
    <table class="history-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Campaign</th>
          <th>Amount (RM)</th>
          <th>Message</th>
          <th>Receipt ID</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($donations as $donation): ?>
          <tr>
            <td><?php echo htmlspecialchars($donation['date']); ?></td>
            <td><?php echo htmlspecialchars($donation['campaign']); ?></td>
            <td><?php echo number_format($donation['amount'], 2); ?></td>
            <td><?php echo htmlspecialchars($donation['message']); ?></td>
            <td><?php echo htmlspecialchars($donation['receipt_id']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <div class="history-back-button">
    <a href="donation_campaigns.php" class="btn btn-back">&larr; Back to Campaigns</a>
  </div>
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