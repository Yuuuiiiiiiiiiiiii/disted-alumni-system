<?php
// process_feedback.php   ← 注意：文件顶部没有任何 HTML 或空行

session_start();
$userId    = $_SESSION['user_id'] ?? 1;
$eventId   = intval($_POST['event_id']   ?? 0);
$rating    = intval($_POST['rating']     ?? 0);
$comment   = trim($_POST['comment']      ?? '');

if($eventId<1 || $rating<1 || $rating>5 || $comment==='') {
    header('Location: feedback_form.php?event_id='.$eventId);
    exit;
}

$conn = new mysqli('localhost','root','','alumni_management_system');
$stmt = $conn->prepare(
  "INSERT INTO feedback 
   (event_id, participant_id, rating, comment) 
   VALUES (?,?,?,?)"
);
$stmt->bind_param('iiis',$eventId,$userId,$rating,$comment);
$stmt->execute();
$fid = $stmt->insert_id;
$stmt->close();
$conn->close();

// **重定向到成功页**，记得 exit;
header('Location: feedback_success.php?feedback_id='.$fid);
exit;
