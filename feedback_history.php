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
      <button class="button-design_header" onclick="toFeedback()">Feedback</button>
    </div>
  </header>
<?php

session_start();

$participantId = $_SESSION['user_id'] ?? 1;

$conn = new mysqli('localhost','root','','alumni_management_system');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("
  SELECT e.title, f.rating, f.comment, f.created_at
  FROM feedback f
  JOIN events e ON f.event_id = e.event_id
  WHERE f.participant_id = ?
  ORDER BY f.created_at DESC
");
$stmt->bind_param("i", $participantId);
$stmt->execute();
$result = $stmt->get_result();
$records = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<div class="feedback-history-container">
  <h2>Your Feedback History</h2>

  <?php if (empty($records)): ?>
    <p class="no-feedback">You have not submitted any feedback yet.</p>
  <?php else: ?>
    <table class="history-table">
      <thead>
        <tr>
          <th>Event</th><th>Rating</th><th>Comments</th><th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($records as $row): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td>
              <?php 
                echo str_repeat("★", $row['rating'])
                   . str_repeat("☆", 5 - $row['rating']);
              ?>
            </td>
            <td><?php echo nl2br(htmlspecialchars($row['comment'])); ?></td>
            <td><?php echo date("F j, Y, g:ia", strtotime($row['created_at'])); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <div class="history-back-button">
    <a href="feedback_list.php" class="btn btn-back">&larr; Back to Events</a>
  </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <div id="footer-placeholder"></div>
  <script src="Button Scripts.js"></script>
</body>
</html>
