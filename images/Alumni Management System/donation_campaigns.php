<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>DISTED Alumni Management System</title>
  <link rel="stylesheet" href="Final Project CSS Designs.css">
  <link rel="stylesheet" href="SwiperSlide.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="donation.css">
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

  $sql    = "SELECT campaign_id, title, description, target_amount, current_amount, image_path 
             FROM campaigns";
  $result = $conn->query($sql);
  ?>

  <div class="donation-container">
    <img src="P2.jpg" alt="Banner Image" style="width:100%; border-radius:6px; margin-bottom: 20px;">

    <h2 class="section-title">Donation & Fundraising</h2>
    <p class="section-desc">
      Support DISTED College’s projects and initiatives by choosing a fundraising campaign below.
    </p>

    <div class="campaign-grid">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="campaign-card">
            <div class="campaign-image">
              <img 
                src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                alt="<?php echo htmlspecialchars($row['title']); ?>" 
              >
            </div>

            <div class="campaign-info">
              <h3 class="campaign-title"><?php echo htmlspecialchars($row['title']); ?></h3>
              <p class="campaign-desc"><?php echo htmlspecialchars($row['description']); ?></p>
              <p class="campaign-stats">
                Target Amount: RM <?php echo number_format($row['target_amount'], 2); ?> &nbsp;|&nbsp;
                Raised So Far: RM <?php echo number_format($row['current_amount'], 2); ?>
              </p>
             
              <a href="#" class="btn btn-moreinfo">More Info</a>
              <a 
                href="donation_form.php?campaign_id=<?php echo $row['campaign_id']; ?>" 
                class="btn btn-donate"
              >
                Donate
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="no-campaigns">No active fundraising campaigns at the moment. Please check back later.</p>
      <?php endif; ?>
    </div>

    <div class="history-link">
      <a href="donation_history.php" class="btn btn-history">
        View Donation History
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
