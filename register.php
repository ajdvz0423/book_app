<?php
session_start();
include 'db-actions/config.php';

$error = "";
$success = "";

// Handle registration submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $role = $_POST["role"];

  if (empty($username) || empty($password) || empty($role)) {
    $error = "All fields are required!";
  } else {
    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
      $error = "Username already exists!";
    } else {
      // Hash the password before storing
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Insert new user
      $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
      if ($stmt->execute([$username, $hashedPassword, $role])) {
        $success = "Registration successful! You can now <a href='login.php'>Login</a>";
      } else {
        $error = "Something went wrong. Please try again!";
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
  <link rel="stylesheet" href="form.css">
  <title>Register</title>
</head>

<body>
  <div class="container">

    <div class="left-container">
      <h4>Manage and view your books!</h4>
      <h1>CREATE ACCOUNT</h1>
    </div>

    <div class="right-container">
      <h2>Register a new account</h2>
      <p>Please fill in the details to create your account.</p>

      <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <p style="color:green;"><?php echo $success; ?></p>
      <?php endif; ?>

      <form action="register.php" method="post">
        <input type="text" id="username" name="username" placeholder="Username" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <select name="role" required>
          <option value="teacher">Teacher</option>
          <option value="student">Student</option>
        </select>
        <input type="submit" value="Register" class="register-btn">
      </form>

      <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
  </div>
</body>

</html>