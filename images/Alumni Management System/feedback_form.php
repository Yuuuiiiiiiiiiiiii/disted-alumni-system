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

$host = "localhost";
$user = "root";
$pass = "";
$db   = "alumni_management_system";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$eventId = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
$event    = null;
if ($eventId > 0) {
    $stmt = $conn->prepare("SELECT title, event_date, image_path FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $stmt->bind_result($title, $date, $imagePath);
    if ($stmt->fetch()) {
        $event = [
            'id'    => $eventId,
            'title' => $title,
            'date'  => $date,
            'image' => $imagePath
        ];
    }
    $stmt->close();
}
?>

<?php if ($event): ?>
  <div class="feedback-form-container">
    <div class="back-button-container">
      <a href="feedback_list.php" class="btn btn-back">&larr; Back to Events</a>
    </div>

    <div class="event-header">
      <img 
        src="<?php echo htmlspecialchars($event['image']); ?>" 
        alt="<?php echo htmlspecialchars($event['title']); ?>" 
      >
      <h2><?php echo htmlspecialchars($event['title']); ?></h2>
      <p style="color:#555; margin-top:4px;">
        <?php echo date("F j, Y", strtotime($event['date'])); ?>
      </p>
    </div>

    <form 
      action="process_feedback.php" 
      method="POST" 
      class="feedback-form"
      novalidate
    >
      <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>" />
      <label for="rating">Your Rating <span class="required">*</span></label>
      <select 
        id="rating" 
        name="rating" 
        class="feedback-select" 
        required
      >
        <option value="">-- Select 1-5 stars --</option>
        <option value="1">★☆☆☆☆</option>
        <option value="2">★★☆☆☆</option>
        <option value="3">★★★☆☆</option>
        <option value="4">★★★★☆</option>
        <option value="5">★★★★★</option>
      </select>
      <div class="validation-message" id="ratingError"></div>

      <label for="comment">Your Comments <span class="required">*</span></label>
      <textarea 
        id="comment" 
        name="comment" 
        class="feedback-textarea" 
        placeholder="Tell us what you think..." 
        required
      ></textarea>
      <div class="validation-message" id="commentError"></div>

      <div class="feedback-submit-group">
        <button type="submit" class="btn btn-submit">Submit Feedback</button>
      </div>
    </form>
  </div>

  <script>
    document.querySelector('.feedback-form').addEventListener('submit', function(e) {
      let valid = true;
      const rating = document.getElementById('rating');
      const comment = document.getElementById('comment');

      if (!rating.value) {
        document.getElementById('ratingError').textContent = 'Please select a rating.';
        valid = false;
      } else {
        document.getElementById('ratingError').textContent = '';
      }

      if (comment.value.trim().length < 5) {
        document.getElementById('commentError').textContent = 'Please write at least 5 characters.';
        valid = false;
      } else {
        document.getElementById('commentError').textContent = '';
      }

      if (!valid) e.preventDefault();
    });

  </script>
<?php else: ?>
  <p style="text-align:center; color:#d00; margin-top:40px;">
    Event not found. <a href="feedback_list.php">Back to Events</a>
  </p>
<?php endif; ?>

<?php
$conn->close();
?>


  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <div id="footer-placeholder"></div>
  <script src="Button Scripts.js"></script>
</body>
</html>
