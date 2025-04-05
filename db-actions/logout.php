<?php
session_start();
session_destroy();
header("Location: /book_app/login.php");
exit;
