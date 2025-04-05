<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $book_id = $_POST["book_id"];
  $user_id = $_POST["user_id"];

  if (empty($book_id) || empty($user_id)) {
    echo "Please select a book and a student.";
    exit;
  }

  try {
    // Check if the book is already assigned to the student
    $stmt = $conn->prepare("SELECT * FROM assignments WHERE book_id = ? AND user_id = ?");
    $stmt->execute([$book_id, $user_id]);
    $existingAssignment = $stmt->fetch();

    if ($existingAssignment) {
      echo "This book is already assigned to this student.";
      exit;
    }

    // If not assigned yet, insert the new assignment
    $stmt = $conn->prepare("INSERT INTO assignments (book_id, user_id) VALUES (?, ?)");
    $stmt->execute([$book_id, $user_id]);

    header("Location: /book_app/dashboard_teacher.php");
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}
