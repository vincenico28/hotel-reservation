<!-- create_admin.php -->
<?php
include 'config.php';

$username = 'admin';
$password = password_hash('admin', PASSWORD_DEFAULT);
$role = 'admin';

$sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $password, $role);
if ($stmt->execute()) {
  echo 'Admin user created successfully.';
} else {
  echo 'Error creating admin user.';
}
?>