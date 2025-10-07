<!-- rooms.php -->
<?php 
session_start(); 
include 'config.php';
include 'functions.php';
$type = $_GET['room-type'] ?? '';
$max_price = $_GET['max-price'] ?? '';
$checkin = $_GET['checkin-date'] ?? '';
$checkout = $_GET['checkout-date'] ?? '';
$sql = "SELECT * FROM rooms WHERE 1=1";
if ($type) $sql .= " AND type = '" . $conn->real_escape_string($type) . "'";
if ($max_price) $sql .= " AND price <= " . (float)$max_price;
$result = $conn->query($sql);
$filtered = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    if (!$checkin || !$checkout || is_available($row['id'], $checkin, $checkout)) {
      $filtered[] = $row;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Reservation - Rooms</title>
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
  <form id="filter-form" action="rooms.php" method="GET">
    <select id="room-type" name="room-type">
      <option value="">All Room Types</option>
      <option value="Single" <?php if ($type == 'Single') echo 'selected'; ?>>Single</option>
      <option value="Double" <?php if ($type == 'Double') echo 'selected'; ?>>Double</option>
      <option value="Suite" <?php if ($type == 'Suite') echo 'selected'; ?>>Suite</option>
    </select>
    <input type="number" id="max-price" name="max-price" placeholder="Max Price per Night" value="<?php echo $max_price; ?>">
    <input type="date" id="checkin-date" name="checkin-date" value="<?php echo $checkin; ?>">
    <input type="date" id="checkout-date" name="checkout-date" value="<?php echo $checkout; ?>">
    <button type="submit" class="button">Filter</button>
  </form>
  <div class="rooms-grid" id="rooms-grid">
    <?php if (empty($filtered)): ?>
      <p>No rooms available for the selected criteria.</p>
    <?php else: ?>
      <?php foreach ($filtered as $room): ?>
        <div class="room-card">
          <img src="<?php echo $room['image']; ?>" alt="<?php echo $room['type']; ?>">
          <div class="room-info">
            <h3><?php echo $room['type']; ?> Room (Room <?php echo $room['room_number']; ?>)</h3>
            <p>$<?php echo $room['price']; ?> per night</p>
            <p>Status: Available</p>
            <a href="booking.php?room=<?php echo urlencode($room['type']); ?>&roomNumber=<?php echo $room['room_number']; ?>" class="button">Reserve</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>