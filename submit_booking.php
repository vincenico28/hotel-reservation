<!-- submit_booking.php -->
<?php
include 'config.php';
include 'functions.php';
session_start();
$data = json_decode(file_get_contents('php://input'), true);
$checkin = $data['checkin'];
$checkout = $data['checkout'];
$guests = $data['guests'];
$roomType = $data['roomType'];
$roomNumber = $data['roomNumber'] ?? null;
$dateIn = new DateTime($checkin);
$dateOut = new DateTime($checkout);
if ($dateOut <= $dateIn) {
  echo json_encode(['success' => false, 'error' => 'Invalid dates']);
  exit;
}
$days = $dateOut->diff($dateIn)->days;
$price_sql = "SELECT price FROM rooms WHERE type = ? LIMIT 1";
$stmt = $conn->prepare($price_sql);
$stmt->bind_param("s", $roomType);
$stmt->execute();
$price_result = $stmt->get_result();
if ($price_result->num_rows == 0) {
  echo json_encode(['success' => false, 'error' => 'Invalid room type']);
  exit;
}
$price = $price_result->fetch_assoc()['price'];
$total = $days * $price;
$room_id = null;
if ($roomNumber) {
  $sql = "SELECT id FROM rooms WHERE room_number = ? AND type = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $roomNumber, $roomType);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $room_id = $result->fetch_assoc()['id'];
    if (!is_available($room_id, $checkin, $checkout)) {
      echo json_encode(['success' => false, 'error' => 'Room not available']);
      exit;
    }
  } else {
    echo json_encode(['success' => false, 'error' => 'Invalid room']);
    exit;
  }
} else {
  $sql = "SELECT id, room_number FROM rooms WHERE type = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $roomType);
  $stmt->execute();
  $result = $stmt->get_result();
  $found = false;
  while ($row = $result->fetch_assoc()) {
    if (is_available($row['id'], $checkin, $checkout)) {
      $room_id = $row['id'];
      $roomNumber = $row['room_number'];
      $found = true;
      break;
    }
  }
  if (!$found) {
    echo json_encode(['success' => false, 'error' => 'No available rooms of this type']);
    exit;
  }
}
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$sql = "INSERT INTO bookings (user_id, room_id, checkin, checkout, guests, total) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissid", $user_id, $room_id, $checkin, $checkout, $guests, $total);
if ($stmt->execute()) {
  echo json_encode([
    'success' => true,
    'details' => [
      'roomType' => $roomType,
      'roomNumber' => $roomNumber,
      'checkin' => $checkin,
      'checkout' => $checkout,
      'guests' => $guests,
      'total' => $total
    ],
    'role' => isset($_SESSION['role']) ? $_SESSION['role'] : null
  ]);
} else {
  echo json_encode(['success' => false, 'error' => 'Error making booking']);
}
?>