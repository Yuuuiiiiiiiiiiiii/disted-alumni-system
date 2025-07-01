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
$host = "localhost";
$user = "root";
$pass = "";
$db   = "alumni_management_system";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$sql    = "SELECT event_id, title, event_date, description, image_path
           FROM events
           ORDER BY event_date DESC";
$result = $conn->query($sql);
?>

<div class="feedback-list-container">
<img src="P3.jpg" alt="Banner Image">
  <h2>Feedback & Review</h2>
  <p class="feedback-desc">
    Please select an event to give feedback. You can also view your past feedback history.
  </p>

  <div class="feedback-list">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="event-card">
          <img 
            src="<?php echo htmlspecialchars($row['image_path']); ?>" 
            alt="<?php echo htmlspecialchars($row['title']); ?>"
          >
          <div class="event-info">
            <h3 class="event-title"><?php echo htmlspecialchars($row['title']); ?></h3>
            <p class="event-date"><?php echo date("F j, Y", strtotime($row['event_date'])); ?></p>
            <p class="event-desc"><?php echo htmlspecialchars($row['description']); ?></p>
            <div class="event-actions">
              <a 
                href="feedback_form.php?event_id=<?php echo $row['event_id']; ?>" 
                class="btn btn-feedback"
              >
                Give Feedback
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="no-events">No past events available for feedback at this time.</p>
    <?php endif; ?>
  </div>

  <div class="history-back-button">
    <a href="feedback_history.php" class="btn btn-back">
      View Feedback History
    </a>
  </div>
</div>

<?php
$conn->close();
?>


  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <div id="footer-placeholder"></div>
  <script src="Button Scripts.js"></script>
</body>
</html>
