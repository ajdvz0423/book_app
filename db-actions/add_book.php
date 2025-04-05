<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST["title"];
  $description = $_POST["description"];
  $cover_image = $_POST["cover_image"];

  $stmt = $conn->prepare("INSERT INTO books (title, description, cover_image) VALUES (?, ?, ?)");
  $stmt->execute([$title, $description, $cover_image]);

  header("Location: /book_app/dashboard_teacher.php");
}
