<!-- admin_dashboard.php -->
<?php
session_start();
include 'config.php';
include 'functions.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
  header('Location: login.php');
  exit;
}
// All bookings
$bookings_sql = "SELECT b.*, r.type, r.room_number, b.user_id FROM bookings b JOIN rooms r ON b.room_id = r.id";
$bookings_result = $conn->query($bookings_sql);
// Available rooms
$avail_checkin = $_GET['avail-checkin'] ?? date('Y-m-d');
$avail_checkout = $_GET['avail-checkout'] ?? date('Y-m-d', strtotime('+1 day'));
$available = [];
$rooms_sql = "SELECT * FROM rooms";
$rooms_result = $conn->query($rooms_sql);
while ($row = $rooms_result->fetch_assoc()) {
  if (is_available($row['id'], $avail_checkin, $avail_checkout)) {
    $available[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Reservation - Admin Dashboard</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <div class="logo">Hotel Lux</div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="rooms.php">Rooms</a></li>
        <li><a href="booking.php">Booking</a></li>
        <li><a href="contact.php">Contact</a></li>
        <?php if (isset($_SESSION['logged_in'])): ?>
          <li><a href="<?php echo $_SESSION['role']; ?>_dashboard.php">Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
  <div class="dashboard">
    <h2>All Bookings</h2>
    <ul class="booking-list">
      <?php if ($bookings_result->num_rows == 0): ?>
        <li>No bookings yet.</li>
      <?php else: ?>
        <?php while ($booking = $bookings_result->fetch_assoc()): ?>
          <li class="booking-item">
            User: <?php echo $booking['user_id'] ? get_username($booking['user_id']) : 'guest'; ?><br>
            Room: <?php echo $booking['type']; ?> (Room <?php echo $booking['room_number']; ?>)<br>
            Check-in: <?php echo $booking['checkin']; ?><br>
            Check-out: <?php echo $booking['checkout']; ?><br>
            Guests: <?php echo $booking['guests']; ?><br>
            Total: $<?php echo $booking['total']; ?>
          </li>
        <?php endwhile; ?>
      <?php endif; ?>
    </ul>
    
    <ul id="available-rooms" class="booking-list">
      <?php if (empty($available)): ?>
        <li>No rooms available for the selected dates.</li>
      <?php else: ?>
        <?php foreach ($available as $room): ?>
          <li class="booking-item">
            Room: <?php echo $room['type']; ?> (Room <?php echo $room['room_number']; ?>)<br>
            Price: $<?php echo $room['price']; ?> per night
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </div>
</body>
</html>