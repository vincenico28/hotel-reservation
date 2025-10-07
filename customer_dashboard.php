<!-- customer_dashboard.php -->
<?php
session_start();
include 'config.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'customer') {
  header('Location: login.php');
  exit;
}
$sql = "SELECT b.*, r.type, r.room_number FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Reservation - Customer Dashboard</title>
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
    <h2>Your Bookings</h2>
    <ul class="booking-list">
      <?php if ($result->num_rows == 0): ?>
        <li>No bookings yet.</li>
      <?php else: ?>
        <?php while ($booking = $result->fetch_assoc()): ?>
          <li class="booking-item">
            Room: <?php echo $booking['type']; ?> (Room <?php echo $booking['room_number']; ?>)<br>
            Check-in: <?php echo $booking['checkin']; ?><br>
            Check-out: <?php echo $booking['checkout']; ?><br>
            Guests: <?php echo $booking['guests']; ?><br>
            Total: $<?php echo $booking['total']; ?>
          </li>
        <?php endwhile; ?>
      <?php endif; ?>
    </ul>
  </div>
</body>
</html>