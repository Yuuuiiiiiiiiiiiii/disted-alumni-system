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

$campaignId = isset($_GET['campaign_id']) ? intval($_GET['campaign_id']) : 0;
$campaignTitle = "XXX Campaign";
$campaignImagePath = "";  
if ($campaignId > 0) {
    $stmt = $conn->prepare("SELECT title, image_path FROM campaigns WHERE campaign_id = ?");
    $stmt->bind_param("i", $campaignId);
    $stmt->execute();
    $stmt->bind_result($campaignTitle, $campaignImagePath);
    $stmt->fetch();
    $stmt->close(); 
}
?>
<div class="donation-form-container">

  <div class="back-button-container">
    <a href="donation_campaigns.php" class="btn btn-back">&larr; Back to Campaigns</a>
  </div>

  <?php if ($campaignImagePath): ?>
    <div class="form-image-container">
      <img 
        src="<?php echo htmlspecialchars($campaignImagePath); ?>" 
        alt="<?php echo htmlspecialchars($campaignTitle); ?>" 
        class="form-campaign-image"
      >
    </div>
  <?php endif; ?>

  <h2 class="form-title">Donate to: <?php echo htmlspecialchars($campaignTitle); ?></h2>
  <p class="form-desc">Please fill in your details below to complete your donation.</p>

  <form 
    action="process_donation.php" 
    method="POST" 
    class="donation-input-form" 
    novalidate
  >
    <input 
      type="hidden" 
      name="campaign_id" 
      value="<?php echo $campaignId; ?>"
    >

    <div class="donation-field-group">
      <label for="donorName" class="donation-label">Name <span class="required">*</span></label>
      <input 
        type="text" 
        id="donorName" 
        name="donorName" 
        class="donation-input" 
        placeholder="Your full name" 
        required
      >
      <span class="validation-message" id="nameError"></span>
    </div>

    <div class="donation-field-group">
      <label for="donorEmail" class="donation-label">Email <span class="required">*</span></label>
      <input 
        type="email" 
        id="donorEmail" 
        name="donorEmail" 
        class="donation-input" 
        placeholder="example@example.com" 
        required
      >
      <span class="validation-message" id="emailError"></span>
    </div>

    <div class="donation-field-group">
      <label for="donorContact" class="donation-label">Contact Number <span class="required">*</span></label>
      <input 
        type="text" 
        id="donorContact" 
        name="donorContact" 
        class="donation-input" 
        placeholder="e.g., 012-3456789" 
        required
      >
      <span class="validation-message" id="contactError"></span>
    </div>

    <div class="donation-field-group">
      <label for="donationAmount" class="donation-label">Donation Amount (RM) <span class="required">*</span></label>
      <input 
        type="number" 
        id="donationAmount" 
        name="donationAmount" 
        class="donation-input" 
        placeholder="e.g., 100.00" 
        min="1" 
        step="0.01" 
        required
      >
      <span class="validation-message" id="amountError"></span>
    </div>

    <div class="donation-field-group">
      <label for="donationMessage" class="donation-label">Message (Optional)</label>
      <textarea 
        id="donationMessage" 
        name="donationMessage" 
        class="donation-textarea" 
        placeholder="Leave a message (e.g., for which purpose)"></textarea>
    </div>

    <div class="donation-submit-group">
      <button type="submit" class="btn btn-submit">Donate Now</button>
    </div>
  </form>
</div>

<?php
$conn->close();
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const form = document.querySelector(".donation-input-form");
  const nameField = document.getElementById("donorName");
  const emailField = document.getElementById("donorEmail");
  const contactField = document.getElementById("donorContact");
  const amountField = document.getElementById("donationAmount");

  form.addEventListener("submit", function(event) {
    let valid = true;

    if (nameField.value.trim() === "") {
      valid = false;
      document.getElementById("nameError").textContent = "Name is required.";
    } else {
      document.getElementById("nameError").textContent = "";
    }
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(emailField.value.trim())) {
      valid = false;
      document.getElementById("emailError").textContent = "Enter a valid email.";
    } else {
      document.getElementById("emailError").textContent = "";
    }
    if (contactField.value.trim().length < 7) {
      valid = false;
      document.getElementById("contactError").textContent = "Enter a valid contact number.";
    } else {
      document.getElementById("contactError").textContent = "";
    }
    if (parseFloat(amountField.value) <= 0 || amountField.value.trim() === "") {
      valid = false;
      document.getElementById("amountError").textContent = "Amount must be greater than 0.";
    } else {
      document.getElementById("amountError").textContent = "";
    }

    if (!valid) {
      event.preventDefault(); 
    }
  });
});
</script>

</body>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<div id="footer-placeholder"></div>
<script src="Button Scripts.js"></script>
</html>

