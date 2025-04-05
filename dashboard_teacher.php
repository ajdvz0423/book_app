<?php
session_start();
include 'db-actions/config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
  header("Location: index.php");
  exit;
}

// Fetch books
$stmt = $conn->query("SELECT * FROM books");
$books = $stmt->fetchAll();

// Fetch students
$studentStmt = $conn->query("SELECT user_id, username FROM users WHERE role = 'student'");
$students = $studentStmt->fetchAll();

// Fetch assigned books with student names
$assignedStmt = $conn->query("
    SELECT assignments.assignment_id, users.username, books.title, books.cover_image, books.description 
    FROM assignments
    JOIN users ON assignments.user_id = users.user_id
    JOIN books ON assignments.book_id = books.book_id
");
$assignedBooks = $assignedStmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="teacher_dashboard.css">
  <title>Teacher Dashboard</title>
</head>

<body>
  <nav>
    <h1>Welcome, <?php echo $_SESSION["username"]; ?> (Teacher)</h1>
    <a href="db-actions/logout.php">
      <button class="logout-button">Logout</button>
    </a>
  </nav>

  <div class="container">
    <div class="left">
      <div class="header">
        <h1>All Books</h1>
        <button class="btn" id="openModalBtn">Add Book</button>
      </div>
      <div class="cards-container">
        <?php foreach ($books as $book): ?>
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
      </div>
    </div>

    <div class="right">
      <div class="header">
        <h1>Assigned Books</h1>
        <button class="btn" id="openModalAssign">Assign a book</button>
      </div>

      <div class="cards-container">
        <?php if (empty($assignedBooks)): ?>
          <p>No books assigned yet.</p>
        <?php else: ?>
          <?php foreach ($assignedBooks as $assigned): ?>
            <div class="card">
              <div class="card-content">
                <div class="book-cover">
                  <img src="<?php echo htmlspecialchars($assigned['cover_image']); ?>" alt="Book Cover">
                </div>
                <div class="book-info">
                  <h2 class="book-title"><?php echo htmlspecialchars($assigned['title']); ?></h2>
                  <p class="book-description"><?php echo htmlspecialchars($assigned['description']); ?></p>
                  <p class="assigned-to">Assigned to: <strong><?php echo htmlspecialchars($assigned['username']); ?></strong></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <!-- Add Book Modal -->
  <div class="modal" id="bookModal">
    <div class="modal-content">
      <span class="close-btn" id="closeModalBtn">&times;</span>
      <h2>Add a New Book</h2>
      <form id="bookForm" method="POST" action="db-actions/add_book.php">
        <label for="title">Book Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="cover_image">Image URL:</label>
        <input type="url" id="cover_image" name="cover_image" required>

        <button type="submit">Add Book</button>
      </form>
    </div>
  </div>

  <!-- Assign Book Modal -->
  <div class="modal" id="assignBookModal">
    <div class="modal-content">
      <span class="close-btn" id="closeAssignModalBtn">&times;</span>
      <h2>Assign a Book</h2>
      <form id="assignBookForm" method="POST" action="db-actions/assign_book.php">
        <label for="book">Select Book:</label>
        <select id="book" name="book_id" required>
          <option value="">-- Select a Book --</option>
          <?php foreach ($books as $book): ?>
            <option value="<?php echo $book['book_id']; ?>">
              <?php echo htmlspecialchars($book['title']); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label for="student">Select Student:</label>
        <select id="student" name="user_id" required>
          <option value="">-- Select a Student --</option>
          <?php foreach ($students as $student): ?>
            <option value="<?php echo $student['user_id']; ?>"><?php echo htmlspecialchars($student['username']); ?></option>
          <?php endforeach; ?>
        </select>

        <button type="submit">Assign Book</button>
      </form>
    </div>
  </div>

  <script src="script/teacher_dashboard.js"></script>
</body>

</html>