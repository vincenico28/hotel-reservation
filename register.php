<!-- register.php -->
<?php
session_start();
include 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  if (!$username || !$password) {
    $error = 'Please fill in all fields';
  } else {
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
      $error = 'Username already exists';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, 'customer')";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $username, $hash);
      if ($stmt->execute()) {
        header('Location: login.php');
        exit;
      } else {
        $error = 'Error registering';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Reservation - Register</title>
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
  <?php if ($error): ?>
    <p style="color: red; text-align: center;"><?php echo $error; ?></p>
  <?php endif; ?>
  <form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit" class="button">Register</button>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </form>
</body>
</html>