<?php
session_start();
include 'db-actions/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = $user["user_id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["role"] = $user["role"];

    // Redirect based on user role
    if ($user["role"] == "teacher") {
      header("Location: dashboard_teacher.php");
    } else {
      header("Location: dashboard_student.php");
    }
    exit;
  } else {
    header("Location: login.php?error=Invalid username or password");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="form.css">
  <title>Login</title>
</head>

<body>
  <div class="container">

    <div class="left-container">
      <h4>Nice to see you again!</h4>
      <h1>WELCOME BACK</h1>
    </div>

    <div class="right-container">
      <h2>Login to your account</h2>
      <p>Please enter your username and password to access your account.</p>

      <?php
      if (isset($_GET['error'])) {
        echo "<p style='color:red;'>" . htmlspecialchars($_GET['error']) . "</p>";
      }
      ?>

      <form action="login.php" method="post">
        <input type="text" id="username" name="username" placeholder="Username" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login" class="login-btn">
      </form>

      <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
  </div>
</body>

</html>