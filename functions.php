<!-- functions.php -->
<?php
function is_available($room_id, $checkin, $checkout) {
  global $conn;
  $sql = "SELECT id FROM bookings WHERE room_id = ? AND NOT (checkout <= ? OR checkin >= ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iss", $room_id, $checkin, $checkout);
  $stmt->execute();
  return $stmt->get_result()->num_rows == 0;
}

function get_username($user_id) {
  global $conn;
  $sql = "SELECT username FROM users WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    return $row['username'];
  }
  return 'Unknown';
}
?>