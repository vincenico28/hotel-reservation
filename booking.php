<!-- booking.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Reservation - Booking</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body onload="initBooking();">
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
  <form onsubmit="submitBooking(event)">
    <label for="checkin">Check-in Date:</label>
    <input type="date" id="checkin" required onchange="updateSummary()">
    <label for="checkout">Check-out Date:</label>
    <input type="date" id="checkout" required onchange="updateSummary()">
    <label for="guests">Number of Guests:</label>
    <input type="number" id="guests" min="1" max="10" required value="1" onchange="updateSummary()">
    <label for="room-type">Room Type:</label>
    <select id="room-type" required onchange="updateSummary()">
      <option value="Single">Single ($100/night)</option>
      <option value="Double">Double ($150/night)</option>
      <option value="Suite">Suite ($250/night)</option>
    </select>
    <button type="submit" class="button">Submit Booking</button>
  </form>
  <div class="summary">
    <h3>Booking Summary</h3>
    <div id="summary"></div>
  </div>
  <div class="modal" id="confirmation-modal">
    <div class="modal-content">
      <span onclick="closeModal()" style="float: right; cursor: pointer; font-size: 24px;">&times;</span>
      <h2>Confirmation</h2>
      <div id="modal-details"></div>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>