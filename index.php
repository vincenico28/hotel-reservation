<!-- index.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Reservation - Home</title>
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
  <section class="hero">
    <h1>Welcome to Hotel Lux</h1>
    <p>Experience luxury and comfort like never before.</p>
    <a href="booking.php" class="button">Book Now</a>
  </section>
  <section class="amenities">
    <div class="amenity">
      <img src="/hotel/img/WiFi.png" alt="WiFi">
      <p>Free WiFi</p>
    </div>
    <div class="amenity">
      <img src="/hotel/img/pool.jpg" alt="Pool">
      <p>Swimming Pool</p>
    </div>
    <div class="amenity">
      <img src="/hotel/img/restu.jpg" alt="Restaurant">
      <p>Restaurant</p>
    </div>
    <div class="amenity">
      <img src="/hotel/img/spa.jpg" alt="Spa">
      <p>Spa</p>
    </div>
  </section>
  <footer class="footer">
    <p>Contact: info@luxhotel.com | Phone: +1-234-567-890</p>
    <p>Social: <a href="#">Facebook</a> | <a href="#">Twitter</a> | <a href="#">Instagram</a></p>
  </footer>
</body>
</html>