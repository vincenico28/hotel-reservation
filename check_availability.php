<!-- check_availability.php -->
<?php
include 'config.php';
include 'functions.php';
$roomType = $_GET['room_type'];
$roomNumber = $_GET['room_number'] ?? null;
$checkin = $_GET['checkin'];
$checkout = $_GET['checkout'];
$available = false;
if ($roomNumber) {
  $sql = "SELECT id FROM rooms WHERE room_number = ? AND type = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $roomNumber, $roomType);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows == 0) {
    echo json_encode(['available' => false]);
    exit;
  }
  $room_id = $result->fetch_assoc()['id'];
  $available = is_available($room_id, $checkin, $checkout);
} else {
  $sql = "SELECT id FROM rooms WHERE type = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $roomType);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    if (is_available($row['id'], $checkin, $checkout)) {
      $available = true;
      break;
    }
  }
}
echo json_encode(['available' => $available]);
?>