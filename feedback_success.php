<?php
// feedback_success.php
session_start();

// Temporarily default user if not logged in
$userId = $_SESSION['user_id'] ?? 1;

// Get feedback_id
$feedbackId = isset($_GET['feedback_id']) ? intval($_GET['feedback_id']) : 0;
if ($feedbackId <= 0) {
    header('Location: feedback_list.php');
    exit;
}

// 1. Connect to database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "alumni_management_system";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
// 2. Fetch feedback + event details
$stmt = $conn->prepare("
    SELECT e.title, f.rating, f.comment
      FROM feedback f
      JOIN events   e ON f.event_id = e.event_id
     WHERE f.feedback_id = ?
");

$stmt->bind_param("i", $feedbackId);
$stmt->execute();
$stmt->bind_result($eventTitle, $rating, $comment);
if (!$stmt->fetch()) {
    // Not found
    $stmt->close();
    $conn->close();
    header('Location: feedback_list.php');
    exit;
}
$stmt->close();
$conn->close();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>DISTED Alumni Management System</title>
  <link rel="stylesheet" href="Final Project CSS Designs.css">
  <link rel="stylesheet" href="SwiperSlide.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="feedback.css">
</head>
<body>
  <header>
    <section class="header-container">
      <a href="Main Page.html">
        <img src="Disted Logo(1).jpg" alt="Disted Logo" class="logo">
      </a>
      <a href="PROFILE (2).html">
        <img class="imgcontainer" src="user.png" alt="Avatar">
      </a>
    </section>

    <p class="p1">Main Site</p>
    <div class="button-container_header">
      <button class="button-design_header" onClick="toAAndSHighlights()">Student Highlights</button>
      <button class="button-design_header" onclick="toMAndJPostings()">Mentorship & Jobs</button>
      <button class="button-design_header">Communication</button>
      <button class="button-design_header" onclick="toDonation()">Donation</button>
      <button class="button-design_header">Feedback</button>
    </div>
  </header>
<body>
<div class="feedback-success-container">
  <div class="feedback-icon">
    <img src="feedbacksuccess.gif" alt="...">
  </div>
  <h2 class="success-title">Thank You for Your Feedback!</h2>
  <p class="success-desc">
    Your feedback is greatly submited. Below is a summary of your feedback:
  </p>

  <div class="feedback-summary">
    <p><strong>Event: </strong> <?php echo htmlspecialchars($eventTitle); ?></p>
    <p><strong>Rating: </strong>
      <?php echo str_repeat("★", $rating) . str_repeat("☆", 5 - $rating); ?>
    </p>
    <p><strong>Comments: </strong>
      <?php echo nl2br(htmlspecialchars($comment)); ?>
    </p>
  </div>

  <div class="success-buttons">
    <a href="feedback_list.php"    class="btn btn-back">← Back to Events</a>
    <a href="feedback_history.php" class="btn btn-primary">View Your Feedback</a>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <div id="footer-placeholder"></div>
  <script src="Button Scripts.js"></script>
</body>
</html>
