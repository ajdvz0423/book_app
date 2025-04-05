<?php
session_start();
include 'db-actions/config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
  header("Location: index.php");
  exit;
}

// Fetch assigned books for the student
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT books.title, books.description, books.cover_image 
                       FROM assignments 
                       JOIN books ON assignments.book_id = books.book_id 
                       WHERE assignments.user_id = ?");
$stmt->execute([$user_id]);
$assignedBooks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="student_dashboard.css">
  <title>Student Dashboard</title>
</head>

<body>
  <nav>
    <h1>Welcome, <?php echo $_SESSION["username"]; ?> (Student)</h1>
    <a href="db-actions/logout.php">
      <button class="logout-button">Logout</button>
    </a>
  </nav>

  <div class="container">
    <div class="left">
      <div class="header">
        <h1>Your Assigned Books</h1>
      </div>
      <div class="cards-container">
        <?php if ($assignedBooks): ?>
          <?php foreach ($assignedBooks as $book): ?>
            <div class="card">
              <div class="card-content">
                <div class="book-cover">
                  <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Book Cover">
                </div>
                <div class="book-info">
                  <h2 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h2>
                  <p class="book-description"><?php echo htmlspecialchars($book['description']); ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>You have no assigned books yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

</body>

</html>